# Sale Coupon - WooCommerce Coupon Sales Plugin

🌐 Read this in other languages: [English](README.md) | [Türkçe](README.tr.md)

A modular WordPress plugin allowing customers to purchase custom-amount, single-use, unique gift coupons in the store's default currency and manage them directly in their "My Account" area.

## Features

*   **Custom Product Type (`sale_coupon`):** Store owners can add a new "Coupon Product" from the WooCommerce product data panel. The price is dynamically defined by the customer at the front-end.
*   **Preset Buttons + Custom Amount Input:** Sellers can configure preset price buttons (e.g., 25$, 50$, 100$) and/or a custom amount text entry field on the product details page.
*   **Prevent Discounts on Coupon Purchases:** As a security measure, coupon codes cannot be applied to orders that contain a coupon product in the cart.
*   **Dynamic Price Display:** Automatically shows the minimum and maximum price range (e.g., $10 - $1000) on archive and shop pages instead of 0 TL.
*   **Single Quantity Enforcement:** Only one coupon product can be added to the cart at a time.
*   **Secure & Unique Code Generation:** Cryptographically random, unique coupon codes are generated, excluding visually similar characters (like 0/O, 1/I/L) to prevent human errors.
*   **My Account Integration:** Customers can view and copy their purchased coupon codes directly from the "My Account > My Coupons" tab.
*   **Email Notifications:** Sends a beautifully styled WooCommerce email notification containing the coupon code and order details once the purchase is complete.

## Requirements

*   WordPress 6.0 or higher
*   WooCommerce 8.0 or higher
*   PHP 7.4 or higher

## Installation

### 1. Simple Installation with Pre-packaged ZIP (Recommended)
The easiest way to install the plugin is using the pre-compiled version:
1. Go to the GitHub Releases page and download the latest **`sale-coupon.zip`** asset (make sure to download the compiled `sale-coupon.zip` and not the automatically generated "Source code" zip).
2. Go to your WordPress Dashboard, navigate to **Plugins > Add New > Upload Plugin**, select the zip file, upload and activate it.
3. This package contains all pre-compiled assets and Composer dependencies (`vendor/` directory), so you do not need CLI or Composer access on your hosting.

### 2. Developer Installation from Source
If you wish to contribute to the code or build from source:
1. Clone the repository or extract the files directly into your `wp-content/plugins/sale-coupon` directory.
2. Run the following commands in the plugin directory to install dependencies and compile files:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```
3. Activate the **Sale Coupon** plugin from the WordPress Dashboard.

### Crucial Post-Installation Step
After activating the plugin, go to **Settings > Permalinks** in your WordPress dashboard and click **Save Changes** without making any modifications. This flushes the rewrite rules, which is mandatory for the "My Coupons" endpoint under the "My Account" page to resolve correctly.

## Configuration

### Global Settings
Navigate to **WooCommerce > Settings > Sale Coupon** to customize the default parameters:
*   Default coupon prefix (e.g., GIFT-)
*   Random character length (min: 8 for security)
*   Minimum and maximum coupon limits
*   Default discount type (Fixed cart / Fixed product discount)
*   Email notification toggles

### Product-Specific Settings
When adding a new product, select **Coupon Product** from the **Product Data** dropdown. You can override the default global limits, prefixes, and preset buttons specifically for this product under the "Coupon Settings" tab.

## License and Dual Licensing

This plugin is distributed under a dual-licensing model: **GNU AGPLv3 (Affero General Public License v3)** and a **Commercial License**.

*   **Open Source Usage (AGPLv3):** You can use this plugin for free in open-source projects or personal sites. However, if you modify the code and run it on a network/website, you **must** release your modified source code under the AGPLv3 license. You cannot make the code proprietary or resell it as-is.
*   **Commercial Usage License:** If you wish to integrate the plugin code into proprietary (closed-source) projects, resell it without releasing the source code, or be exempt from AGPLv3 restrictions, you must purchase a commercial license by contacting the author.

For details, please refer to the [LICENSE](file:///c:/Users/fikri/Desktop/avdini.com/Sale_Coupon/LICENSE) file.
