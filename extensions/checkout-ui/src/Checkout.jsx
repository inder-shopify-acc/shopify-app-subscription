import {
  reactExtension,
  BlockStack,
  View,
  Heading,
  Link,
  Text,
  useApi,
  BlockLayout,
} from "@shopify/ui-extensions-react/checkout";

export default reactExtension("purchase.thank-you.block.render", () => (
  <Extension />
));
function Extension() {
  const shopData = useApi();
  const shopUrl = shopData.shop.storefrontUrl;
  const appProxy = `${shopUrl}/apps/your-subscriptions`;
  return (
    <BlockLayout>
      <View padding>
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
      </View>
    </BlockLayout>
  );
}
