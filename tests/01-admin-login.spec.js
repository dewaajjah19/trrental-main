const { test, expect } = require('@playwright/test');

test.describe('Black-box Testing - Login Admin', () => {
  test('Login admin berhasil dengan username dan password benar', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/auth/login');

    await page.getByPlaceholder('Masukkan Username').fill('dewaadmin');
    await page.getByPlaceholder('Masukkan Password').fill('admin123');

    await page.getByRole('button', { name: 'Login' }).click();

    // Pastikan berhasil masuk ke halaman dashboard
    await expect(page).toHaveURL(/dashboard/);

    // Pakai heading agar tidak bentrok dengan menu sidebar Dashboard
    await expect(
      page.getByRole('heading', { name: 'Dashboard' })
    ).toBeVisible();
  });

  test('Login admin gagal dengan password salah', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/auth/login');

    await page.getByPlaceholder('Masukkan Username').fill('dewaadmin');
    await page.getByPlaceholder('Masukkan Password').fill('salah123');

    await page.getByRole('button', { name: 'Login' }).click();

    // Cek alert error khusus, bukan semua teks username/password
    const errorAlert = page.locator('.login-alert');

    await expect(errorAlert).toBeVisible();
    await expect(errorAlert).toContainText(/username atau password salah/i);
  });
});