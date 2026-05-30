const { test, expect } = require('@playwright/test');

test.describe('Black-box Testing - Products Search', () => {
  test('Search armada dengan keyword benar menampilkan data armada', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/home/products');

    // Cari input search berdasarkan placeholder
    const searchInput = page.getByPlaceholder(/Explore Vehicle|Search vehicle/i);

    // Input keyword yang sesuai dengan data armada
    await searchInput.fill('Scoopy');

    // Cek armada Brio tampil
    await expect(
      page.locator('.vehicle-item').filter({ hasText: /Scoopy/i }).first()
    ).toBeVisible();
  });

  test('Search armada dengan keyword tidak sesuai menampilkan pesan kosong', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/home/products');

    const searchInput = page.getByPlaceholder(/Explore Vehicle|Search vehicle/i);

    // Input keyword yang tidak ada di data armada
    await searchInput.fill('zzzzvehicle');

    // Cek pesan no vehicles muncul
    await expect(page.getByText(/No vehicles found/i)).toBeVisible();
    await expect(page.getByText(/Please try another keyword/i)).toBeVisible();
  });

  test('Search dikosongkan kembali menampilkan semua data armada', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/home/products');

    const searchInput = page.getByPlaceholder(/Explore Vehicle|Search vehicle/i);

    // Search data yang ada
    await searchInput.fill('Scoopy');

    await expect(
      page.locator('.vehicle-item').filter({ hasText: /Scoopy/i }).first()
    ).toBeVisible();

    // Kosongkan search
    await searchInput.fill('');

    // Cek minimal ada card armada yang tampil
    await expect(page.locator('.vehicle-item').first()).toBeVisible();
  });

  test('Search typo lalu diperbaiki menampilkan data kembali', async ({ page }) => {
    await page.goto('http://localhost/trrental-main/home/products');

    const searchInput = page.getByPlaceholder(/Explore Vehicle|Search vehicle/i);

    // Input typo
    await searchInput.fill('Scoopy');

    await expect(page.getByText(/No vehicles found/i)).toBeVisible();

    // Perbaiki keyword
    await searchInput.fill('Scoopy');

    await expect(
      page.locator('.vehicle-item').filter({ hasText: /Scoopy/i }).first()
    ).toBeVisible();
  });
});