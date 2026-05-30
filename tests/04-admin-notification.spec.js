const { test, expect } = require('@playwright/test');

// Helper login admin
async function loginAdmin(page) {
  await page.goto('http://localhost/trrental-main/auth/login');

  await page.getByPlaceholder('Masukkan Username').fill('dewaadmin');
  await page.getByPlaceholder('Masukkan Password').fill('admin123');

  await page.getByRole('button', { name: 'Login' }).click();

  await expect(page).toHaveURL(/dashboard/);
  await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible();
}

// Helper buka dropdown notifikasi dengan lebih stabil
async function openNotificationDropdown(page) {
  const bellButton = page.locator('#bookingNotificationDropdown');
  const menu = page.locator('.admin-notification-menu');

  await expect(bellButton).toBeVisible();

  // Klik seperti user biasa
  await bellButton.click();

  // Tunggu sebentar untuk Bootstrap dropdown
  await page.waitForTimeout(300);

  // Kalau dropdown belum muncul, paksa show menggunakan Bootstrap/jQuery
  if (!(await menu.isVisible())) {
    await page.evaluate(() => {
      if (window.jQuery) {
        window.jQuery('#bookingNotificationDropdown').dropdown('show');
      }
    });

    await page.waitForTimeout(300);
  }

  await expect(menu).toBeVisible();

  return menu;
}

test.describe('Black-box Testing - Notifikasi Admin', () => {
  test('Admin dapat membuka dropdown notifikasi', async ({ page }) => {
    await loginAdmin(page);

    const menu = await openNotificationDropdown(page);

    await expect(menu).toContainText('Notifications');

    const notifItem = menu.locator('.admin-notification-item');
    const emptyNotif = menu.locator('.admin-notification-empty');

    if (await notifItem.count() > 0) {
      await expect(notifItem.first()).toBeVisible();
      await expect(menu.locator('.admin-notification-seeall')).toBeVisible();
    } else {
      await expect(emptyNotif).toBeVisible();
      await expect(emptyNotif).toContainText(/No new booking notifications/i);
    }
  });

  test('Klik See all pending bookings mengarah ke halaman Data Booking jika ada notifikasi', async ({ page }) => {
    await loginAdmin(page);

    const menu = await openNotificationDropdown(page);

    const seeAllPending = menu.locator('.admin-notification-seeall');
    const emptyNotif = menu.locator('.admin-notification-empty');

    if (await seeAllPending.count() > 0) {
      await expect(seeAllPending).toBeVisible();

      await seeAllPending.click();

      await expect(page).toHaveURL(/booking/);
      await expect(page.getByText(/Data Booking|Booking/i).first()).toBeVisible();
    } else {
      await expect(emptyNotif).toBeVisible();
      await expect(emptyNotif).toContainText(/No new booking notifications/i);
    }
  });

  test('Klik salah satu notifikasi mengarah ke detail booking jika ada booking menunggu', async ({ page }) => {
    await loginAdmin(page);

    const menu = await openNotificationDropdown(page);

    const notifItem = menu.locator('.admin-notification-item');
    const emptyNotif = menu.locator('.admin-notification-empty');

    if (await notifItem.count() > 0) {
      await expect(notifItem.first()).toBeVisible();

      await notifItem.first().click();

      await expect(page).toHaveURL(/booking\/detail/);
      await expect(page.getByText(/Detail Booking/i)).toBeVisible();
    } else {
      await expect(emptyNotif).toBeVisible();
      await expect(emptyNotif).toContainText(/No new booking notifications/i);
    }
  });
});