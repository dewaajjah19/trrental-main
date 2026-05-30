const { test, expect } = require('@playwright/test');

// Helper login admin agar tidak tulis ulang di setiap test
async function loginAdmin(page) {
  await page.goto('http://localhost/trrental-main/auth/login');

  await page.getByPlaceholder('Masukkan Username').fill('dewaadmin');
  await page.getByPlaceholder('Masukkan Password').fill('admin123');

  await page.getByRole('button', { name: 'Login' }).click();

  await expect(page).toHaveURL(/dashboard/);
}

test.describe('Black-box Testing - Laporan Admin', () => {
  test('Admin berhasil membuka halaman laporan', async ({ page }) => {
    await loginAdmin(page);

    await page.goto('http://localhost/trrental-main/laporan');

    // Cek halaman laporan terbuka
    await expect(page.locator('.page-title')).toContainText('Laporan');

    // Cek bagian penting di halaman laporan tampil
    await expect(page.getByText('Filter Laporan')).toBeVisible();
    await expect(page.getByText('Data Booking Terbaru')).toBeVisible();
  });

  test('Admin berhasil export laporan ke PDF', async ({ page }) => {
    await loginAdmin(page);

    await page.goto('http://localhost/trrental-main/laporan');

    // Tunggu tombol export PDF tersedia
    await expect(page.locator('#btnExportPDF')).toBeVisible();

    // Tunggu event download ketika tombol diklik
    const downloadPromise = page.waitForEvent('download');

    await page.locator('#btnExportPDF').click();

    const download = await downloadPromise;

    // Cek nama file hasil download adalah PDF
    expect(download.suggestedFilename()).toContain('.pdf');
  });

  test('Admin berhasil export laporan ke Excel', async ({ page }) => {
    await loginAdmin(page);

    await page.goto('http://localhost/trrental-main/laporan');

    // Tunggu tombol export Excel tersedia
    await expect(page.locator('#btnExportExcel')).toBeVisible();

    // Tunggu event download ketika tombol diklik
    const downloadPromise = page.waitForEvent('download');

    await page.locator('#btnExportExcel').click();

    const download = await downloadPromise;

    // Cek nama file hasil download adalah Excel
    expect(download.suggestedFilename()).toContain('.xlsx');
  });
});