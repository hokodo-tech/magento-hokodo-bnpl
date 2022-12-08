import { Page } from "@playwright/test";
import { HokodoIds } from "./types/HokodoEntityIds";
import { MagentoOrder, MagentoOrderCaptureStatus } from "./types/MagentoOrder";

export function getHokodoIdsFromMagentoOrder(magentoOrder: MagentoOrder): HokodoIds {
    return {
        order: magentoOrder?.extension_attributes?.payment_additional_info?.find(z => z.key === "hokodo_order_id")?.value || "",
        deferredPayment: magentoOrder?.extension_attributes?.payment_additional_info?.find(z => z.key === "hokodo_deferred_payment_id")?.value || "",
    }
}

export function getCaptureStatus(magentoOrder: MagentoOrder): MagentoOrderCaptureStatus {
    console.log(magentoOrder);
    if (magentoOrder.base_total_invoiced === undefined) {
        return MagentoOrderCaptureStatus.NotInvoiced;
    }

    if (magentoOrder.status_histories.find(x => x.comment.toLocaleLowerCase().includes("captured amount of"))) {
        return MagentoOrderCaptureStatus.Captured;
    }

    return MagentoOrderCaptureStatus.Invoiced;
}

export async function isLoggedIn(page: Page): Promise<boolean> {
    await page.waitForLoadState("networkidle");
    await page.locator(".welcome").first().waitFor();
    return await page.locator(".logged-in").count() > 0;
}