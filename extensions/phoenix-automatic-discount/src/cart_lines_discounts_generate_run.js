import {
  OrderDiscountSelectionStrategy,
  ProductDiscountSelectionStrategy,
  DiscountClass,
} from "../generated/api";
 
/**
 * @typedef {import("../generated/api").CartInput} RunInput
 * @typedef {import("../generated/api").CartLinesDiscountsGenerateRunResult} CartLinesDiscountsGenerateRunResult
 * @typedef {import("../generated/api").ProductDiscountCandidateTarget} ProductDiscountCandidateTarget
 */
 
/**
 * @param {RunInput} input
 * @returns {CartLinesDiscountsGenerateRunResult}
 */
export function cartLinesDiscountsGenerateRun(input) {
  if (!input.cart.lines.length) {
    throw new Error("No cart lines found");
  }
 
  const {
    cartLinePercentage,
    collectionIds,
    productId: excludedProductId,
    status,
    title
  } = parseMetafield(input.discount.metafield);
  function parseMetafield(metafield) {
    try {
      const value = JSON.parse(metafield.value);
   
      return {
        cartLinePercentage: value.cartLinePercentage || 0,
        // orderPercentage: value.orderPercentage || 0,
        collectionIds: value.collectionIds || [],
        productId: value.productId || null, // Excluded productId
        status:value.status,
        title:value.title
      };
    } catch (error) {
      console.error("Error parsing metafield", error);
   
      return {
        cartLinePercentage: 0,
        orderPercentage: 0,
        collectionIds: [],
        productId: null,
        status:null,
        title:''
      };
    }
  }
  const hasOrderDiscountClass = input.discount.discountClasses.includes(
    DiscountClass.Order,
  );
 
  const hasProductDiscountClass = input.discount.discountClasses.includes(
    DiscountClass.Product,
  );
 
  if (!hasOrderDiscountClass && !hasProductDiscountClass) {
    return { operations: [] };
  }
 
  const operations = [];
  // if(input.cart.lines.find((e) => e.id != excludedProductId)) {
  //     return { operations: [] };
  // }
  // Apply product discounts if allowed
  // console.log(JSON.stringify(input.cart.lines),'rana');
  const isExcludedProductInCart = input.cart.lines.some((line) =>
 
 
  // if(line.merchandise.__typename === "ProductVariant" ){
    line.merchandise.product?.id == excludedProductId
     
  // }
)
   
 
// &&
  // line.merchandise.product?.id === excludedProductId
 
// );
 
 
 
if (hasOrderDiscountClass && cartLinePercentage > 0 && isExcludedProductInCart && status == 'Active') {
  // ✅ Check if excluded product is present in the cart
  // const isExcludedProductInCart = input.cart.lines.some(
  //   (line) =>
  //     "product" in line.merchandise &&
  //     line.merchandise.product.id === excludedProductId
  // );
 
  // if (isExcludedProductInCart) {
    const cartLineTargets = input.cart.lines.reduce(
      (/** @type {ProductDiscountCandidateTarget[]} */ targets, line) => {
        // console.log(line.merchandise, "line");
                if ("product" in line.merchandise) {
             console.log(JSON.stringify(line),'rana');
          }
        if (
          "product" in line.merchandise &&
          line.merchandise.product.id !== excludedProductId && // NOT EQUAL
          line.merchandise.product.inAnyCollection === true
        ) {
          targets.push({
            cartLine: {
              id: line.id,
            },
          });
        }
 
        return targets;
      },
      [],
    );
 
    if (cartLineTargets.length > 0) {
      operations.push({
        orderDiscountsAdd: {
          candidates: [
            {
              message: `${title} ${cartLinePercentage}% OFF TOTAL`,
              targets: [
                {
                  orderSubtotal: {
                    excludedCartLineIds: input.cart.lines
                      .filter(
                        (line) =>
                          "product" in line.merchandise &&
                          line.merchandise.product.id === excludedProductId
                      )
                      .map((line) => line.id),
                  },
                },
              ],
              value: {
                percentage: {
                  value: cartLinePercentage,
                },
              },
            },
          ],
          selectionStrategy: OrderDiscountSelectionStrategy.First,
        },
      });
    }
  }
 
  return { operations };
}