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
import { useState, useEffect } from "react";
export default reactExtension(
  "customer-account.order-index.block.render",
  () => <OrderIndexPage1 />
);

function OrderIndexPage1() {
  const { i18n } = useApi();
  const shopData = useApi();
  const [appProxy, setAppProxy] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    async function fetchData() {
      try {
        const sessionToken = await shopData.sessionToken.get(); // Fetch session token
        console.log('sessionToken535435',shopData);
        const url =
          "https://yulanda-unpanelled-superzealously.ngrok-free.dev/application/controller/ajaxhandler.php?action=get_store_name";
        const response = await fetch(url, {
          method: "GET",
          headers: {
            Authorization: `${sessionToken}`, // Pass Shopify token
            "Content-Type": "application/json",
            "ngrok-skip-browser-warning": "true",
          },
        });

        if (!response.ok) {
          throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        setAppProxy(`https://${json.shop}/apps/your-subscriptions-`);
        // console.log("Fetched Data:", json);
      } catch (error) {
        console.error("Fetch error:", error.message);
        setError(error.message);
      }
    }

    fetchData();
  }, []); //

  // console.log(i18n, 'customer cccccc',shopData, 'sessionToken =>', sessionToken)
  // const shopUrl =  shopData.shop.storefrontUrl
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
