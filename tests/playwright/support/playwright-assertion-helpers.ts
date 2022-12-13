import { expect, Page } from "@playwright/test";
import { Address } from "./types/Address";
import { HokodoOrder } from "./types/HokodoOrder";
import { MagentoOrder } from "./types/MagentoOrder";

export function verifyHokodoOrder (hokodoOrder: HokodoOrder, basketTotals: MagentoOrder) {
    expect(basketTotals.grand_total).toBe(hokodoOrder.total_amount / 100);
    expect(basketTotals.base_currency_code).toBe(hokodoOrder.currency);
    expect(basketTotals.tax_amount).toBe(hokodoOrder.tax_amount / 100);

    for (const basketItem of basketTotals.items) {
      const orderItem = hokodoOrder.items.find(x => parseInt(x.item_id) === basketItem.item_id);

      expect(orderItem, `The '${basketItem.name}' was not found in the Hokodo Order`).toBeTruthy();
      expect(basketItem.name).toBe(orderItem?.description);
      expect(basketItem.qty).toBe(parseFloat(orderItem?.quantity || "0"));
      expect(basketItem.price).toBe((orderItem?.unit_price || 1) / 100);
      expect(basketItem.tax_amount).toBe(orderItem?.tax_amount);
    }

    const shippingOrderItems = hokodoOrder.items.filter(x => x.type === "shipping");
    
    if (basketTotals.shipping_amount > 0) {
      const shippingOrderItems = hokodoOrder.items.filter(x => x.type === "shipping");

      expect(shippingOrderItems, "The Order did not contain a single line item for Shipping").toHaveLength(1);

      expect (basketTotals.shipping_amount).toBe(shippingOrderItems[0].unit_price / 100);
      expect (shippingOrderItems[0].quantity).toBe("1.000");
    } else {
      expect(shippingOrderItems, "The Order has a Line Item for Shipping, but the shipping is free").toHaveLength(0);
    }
}

export function verifyAddressDetails(magentoAddress: Address, hokodoAddress: Address) {
  expect(hokodoAddress.address_line1).toBe(magentoAddress.address_line1);
  expect(hokodoAddress.address_line2).toBe(magentoAddress.address_line2);
  expect(hokodoAddress.city).toBe(magentoAddress.city);
  expect(hokodoAddress.postcode).toBe(magentoAddress.postcode);
  expect(hokodoAddress.country).toBe(magentoAddress.country);
}

export async function elementExists(page: Page, locator: string) {
  const locators = await page.$$(locator);
  
  return locators.length > 0;
}