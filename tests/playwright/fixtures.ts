/* eslint-disable camelcase */
import { Page, test as base, TestInfo } from "@playwright/test";
import cp from "child_process";
import CreateAccountPage from "./page-objects/create-account-page";
import HomePage from "./page-objects/home-page";
import ProductDetailsPage from "./page-objects/product-details-page";
import { generateAddress, generateBuyerData } from "./support/factories";
import ShippingAddressPage from "./page-objects/checkout/shipping-address-page";
import PaymentPage from "./page-objects/checkout/payment-page";
import { OrderData } from "./support/types/OrderData";
import CheckoutSuccessPage from "./page-objects/checkout/checkout-success-page";
import AdminLoginPage from "./page-objects/admin/admin-login-page";
import AdminHomePage from "./page-objects/admin/admin-home-page";
import ListOrdersPage from "./page-objects/admin/list-orders-page";
import OrderPage from "./page-objects/admin/order-page";
import ShipOrderPage from "./page-objects/admin/ship-order-page";
import { HokodoAPI } from "./support/hokodo-api";
import { BuyerStatus, CompanyType, CreditStatus, FraudStatus } from "./support/types/Buyer";
import { HokodoOrder } from "./support/types/HokodoOrder";
import { MagentoBasketDetails } from "./support/types/MagentoBasketDetails";

export type TestFixtures = {
  page: Page;
  createAccountPage: CreateAccountPage;
  homePage: HomePage;
  productDetailsPage: ProductDetailsPage;
  shippingAddressPage: ShippingAddressPage;
  paymentPage: PaymentPage;
  checkoutSuccessPage: CheckoutSuccessPage;
  generateOrderData(companyType?: CompanyType, buyerStatus?: BuyerStatus): Promise<OrderData>;
  adminLoginPage: AdminLoginPage;
  adminHomePage: AdminHomePage;
  listOrdersPage: ListOrdersPage;
  orderPage: OrderPage;
  shipOrderPage: ShipOrderPage;
  hokodoApi: HokodoAPI;
  abortSegmentApiCalls: Function;
};

const clientPlaywrightVersion = cp
  .execSync("npx playwright --version")
  .toString()
  .trim()
  .split(" ")[1];

// BrowserStack Specific Capabilities.
const caps = {
  browser: "chrome",
  os: "osx",
  os_version: "catalina",
  name: "My first playwright test",
  build: process.env.VERSION,
  browser_version: "latest",
  "browserstack.username": process.env.BROWSERSTACK_USERNAME || "YOUR_USERNAME",
  "browserstack.accessKey": process.env.BROWSERSTACK_ACCESS_KEY || "YOUR_ACCESS_KEY",
  "browserstack.local": process.env.BROWSERSTACK_LOCAL || false,
  "client.playwrightVersion": clientPlaywrightVersion,
  "browserstack.playwrightVersion": clientPlaywrightVersion,
  "browserstack.networkLogs": true,
  "browserstack.console": "errors",
};

// exports.BS_LOCAL_ARGS = {
//   key: process.env.BROWSERSTACK_ACCESS_KEY,
// };

// Patching the capabilities dynamically according to the project name.
const patchCaps = (name: string, title: string) => {
  const combination = name.split(/@browserstack/)[0];
  const [browerCaps, osCaps] = combination.split(/:/);
  // eslint-disable-next-line @typescript-eslint/naming-convention
  const [browser, browser_version] = browerCaps.split(/@/);
  const osCapsSplit = osCaps.split(/ /);
  const os = osCapsSplit.shift();
  // eslint-disable-next-line @typescript-eslint/naming-convention
  const os_version = osCapsSplit.join(" ");
  caps.browser = browser || "chrome";
  caps.browser_version = browser_version || "latest";
  caps.os = os || "osx";
  caps.os_version = os_version || "catalina";
  caps.name = title;
};

// const isHash = (entity: object | Array<string>): boolean =>
//   Boolean(entity && typeof entity === "object" && !Array.isArray(entity));

// const nestedKeyValue = (testInfo: TestInfo, keys: Array<string>) =>
//   keys.reduce((hash, key) => (isHash(hash) ? hash[key] : undefined), testInfo);

const isUndefined = (val: string) => val === undefined || val === null || val === "";

const evaluateSessionStatus = (status: string) => {
  let statusToLower = "";

  if (!isUndefined(status)) {
    statusToLower = status.toLowerCase();
  }
  if (statusToLower === "passed") {
    return "passed";
  }
  if (statusToLower === "failed" || statusToLower === "timedout") {
    return "failed";
  }

  return "";
};

const test = base.extend<TestFixtures>({
  page: async ({ page, playwright }, use, testInfo: TestInfo) => {
    if (testInfo.project.name.match(/browserstack/)) {
      // when we get to browserstack, remember to mock the Segment API to avoid costs. https://gitlab.com/hokodo/engineering/plugins/magento-hokodo-bnpl/-/issues/218
      patchCaps(testInfo.project.name, `${testInfo.titlePath[1]} - ${testInfo.title}`);
      const vBrowser = await playwright.chromium.connect(
        `wss://cdp.browserstack.com/playwright?caps=${encodeURIComponent(JSON.stringify(caps))}`
      );
      const vContext = await vBrowser.newContext(testInfo.project.use);
      const vPage = await vContext.newPage();
      await use(vPage);
      const testResult = {
        action: "setSessionStatus",
        arguments: {
          status: evaluateSessionStatus(testInfo.status || ""),
          // reason: nestedKeyValue(testInfo, ["error", "message"]),
        },
      };
      await vPage.evaluate(() => { }, `browserstack_executor: ${JSON.stringify(testResult)}`);
      await vPage.close();
      await vBrowser.close();
    } else {
      await page.route("https://api.segment.io/**", route => route.fulfill({ status: 200 }));
      void use(page);
    }
  },
  createAccountPage: async ({ page }, use) => {
    await use(new CreateAccountPage(page));
  }, 
  homePage: async ({ page }, use) => {
    await use(new HomePage(page));
  },
  productDetailsPage: async ({ page }, use) => {
    await use(new ProductDetailsPage(page));
  },
  shippingAddressPage: async ({ page }, use) => {
    await use(new ShippingAddressPage(page));
  },
  paymentPage: async ({ page }, use) => {
    await use(new PaymentPage(page));
  },
  checkoutSuccessPage: async ({ page }, use) => {
    await use(new CheckoutSuccessPage(page));
  },
  adminLoginPage: async ({ page }, use) => {
    await use(new AdminLoginPage(page))
  },
  adminHomePage: async ({ page }, use) => {
    await use(new AdminHomePage(page))
  },
  listOrdersPage: async ({ page }, use) => {
    await use(new ListOrdersPage(page))
  },
  orderPage: async ({ page }, use) => {
    await use(new OrderPage(page))
  },
  shipOrderPage: async ({ page }, use) => {
    await use(new ShipOrderPage(page))
  },
  hokodoApi: async ({ page }, use) => {
    await use(new HokodoAPI())
  },
  generateOrderData: ({ }, use) => {
    use(async (companyType: CompanyType = CompanyType.REGISTERED_COMPANY,
      buyerStatus: BuyerStatus = {
        creditStatus: CreditStatus.OFFERED,
        fraudStatus: FraudStatus.ACCEPTED
      }) => {
      return {
        buyer: generateBuyerData(companyType, buyerStatus),
        shippingAddress: generateAddress(),
        billingAddress: generateAddress(),
        products: [{
          name: "Hero Hoodie",
          size: "XS",
          colour: "Green",
          quantity: 2,
        }]
      }
    })
  },
});

export default test;


