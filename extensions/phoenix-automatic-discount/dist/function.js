// extensions/phoenix-automatic-discount/node_modules/@shopify/shopify_function/run.ts
function run_default(userfunction) {
  if (!Javy.JSON) {
    throw new Error("Javy.JSON is not defined. Please rebuild your function using the latest version of Shopify CLI.");
  }
  const input_obj = Javy.JSON.fromStdin();
  const output_obj = userfunction(input_obj);
  Javy.JSON.toStdout(output_obj);
}

// extensions/phoenix-automatic-discount/src/cart_lines_discounts_generate_run.js
function cartLinesDiscountsGenerateRun(input) {
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
        productId: value.productId || null,
        // Excluded productId
        status: value.status,
        title: value.title
      };
    } catch (error) {
      console.error("Error parsing metafield", error);
      return {
        cartLinePercentage: 0,
        orderPercentage: 0,
        collectionIds: [],
        productId: null,
        status: null,
        title: ""
      };
    }
  }
  const hasOrderDiscountClass = input.discount.discountClasses.includes(
    "ORDER" /* Order */
  );
  const hasProductDiscountClass = input.discount.discountClasses.includes(
    "PRODUCT" /* Product */
  );
  if (!hasOrderDiscountClass && !hasProductDiscountClass) {
    return { operations: [] };
  }
  const operations = [];
  const isExcludedProductInCart = input.cart.lines.some(
    (line) => (
      // if(line.merchandise.__typename === "ProductVariant" ){
      line.merchandise.product?.id == excludedProductId
    )
    // }
  );
  if (hasOrderDiscountClass && cartLinePercentage > 0 && isExcludedProductInCart && status == "Active") {
    const cartLineTargets = input.cart.lines.reduce(
      (targets, line) => {
        if ("product" in line.merchandise) {
          console.log(JSON.stringify(line), "rana");
        }
        if ("product" in line.merchandise && line.merchandise.product.id !== excludedProductId && // NOT EQUAL
        line.merchandise.product.inAnyCollection === true) {
          targets.push({
            cartLine: {
              id: line.id
            }
          });
        }
        return targets;
      },
      []
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
                    excludedCartLineIds: input.cart.lines.filter(
                      (line) => "product" in line.merchandise && line.merchandise.product.id === excludedProductId
                    ).map((line) => line.id)
                  }
                }
              ],
              value: {
                percentage: {
                  value: cartLinePercentage
                }
              }
            }
          ],
          selectionStrategy: "FIRST" /* First */
        }
      });
    }
  }
  return { operations };
}

// extensions/phoenix-automatic-discount/src/cart_delivery_options_discounts_generate_run.js
function cartDeliveryOptionsDiscountsGenerateRun(input) {
  const firstDeliveryGroup = input.cart.deliveryGroups[0];
  if (!firstDeliveryGroup) {
    throw new Error("No delivery groups found");
  }
  const hasShippingDiscountClass = input.discount.discountClasses.includes(
    "SHIPPING" /* Shipping */
  );
  if (!hasShippingDiscountClass) {
    return { operations: [] };
  }
  return {
    operations: [
      {
        deliveryDiscountsAdd: {
          candidates: [
            {
              message: "FREE DELIVERY",
              targets: [
                {
                  deliveryGroup: {
                    id: firstDeliveryGroup.id
                  }
                }
              ],
              value: {
                percentage: {
                  value: 100
                }
              }
            }
          ],
          selectionStrategy: "ALL" /* All */
        }
      }
    ]
  };
}

// <stdin>
function cartLinesDiscountsGenerateRun2() {
  return run_default(cartLinesDiscountsGenerateRun);
}
function cartDeliveryOptionsDiscountsGenerateRun2() {
  return run_default(cartDeliveryOptionsDiscountsGenerateRun);
}
export {
  cartDeliveryOptionsDiscountsGenerateRun2 as cartDeliveryOptionsDiscountsGenerateRun,
  cartLinesDiscountsGenerateRun2 as cartLinesDiscountsGenerateRun
};
