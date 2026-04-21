import {
  BlockStack,
  reactExtension,
  TextBlock,
  Banner,
  useApi,
  Button,
  Card,
  Grid,
  Heading,
  Text,
  InlineLayout,
  Icon,
  View,
  Link,
} from "@shopify/ui-extensions-react/customer-account";

export default reactExtension(
  "customer-account.order-status.block.render",
  () => <PromotionBanner />
);

function PromotionBanner() {
  const { i18n } = useApi();
  const shopData = useApi();
  const shopUrl = shopData.shop.storefrontUrl;
  const appProxy = `${shopUrl}/apps/your-subscription`;
  console.log(i18n, "customer cccccc");
  console.log('appProxy',appProxy);
  return (
    <Card padding>
      <BlockStack spacing="loose">
        <Heading>Subscriptions</Heading>
        <BlockStack spacing="base">
          <Text appearance="subdued">
            Continue to your account to view and manage your subscriptions.
          </Text>
        </BlockStack>
      </BlockStack>
      <BlockStack spacing="loose">
        <Link to={appProxy}>Manage Subscriptions</Link>
      </BlockStack>
    </Card>
  );
}
