import {extension} from '@shopify/ui-extensions/admin';
import PurchaseOptionsActionExtension from './PurchaseOptionsActionExtension';

export default extension(
  'admin.product-variant-purchase-option.action.render',
  (root, {i18n, close, data}) => {
    PurchaseOptionsActionExtension(
      'admin.product-variant-purchase-option.action.render',
      root,
      {
        i18n,
        close,
        data,
      },
    );
  },
);