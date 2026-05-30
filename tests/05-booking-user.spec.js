const { test, expect } = require('@playwright/test');
const path = require('path');

const BASE_URL = 'http://localhost/trrental-main';

const files = {
  ktp: path.join(__dirname, 'fixtures', 'ktp.png'),
  sim: path.join(__dirname, 'fixtures', 'sim.png'),
  tiket: path.join(__dirname, 'fixtures', 'tiket.png'),
  hotel: path.join(__dirname, 'fixtures', 'hotel.png'),
};

function dateInputValue(daysFromToday) {
  const date = new Date();
  date.setDate(date.getDate() + daysFromToday);
  return date.toISOString().split('T')[0];
}

async function openFirstVehicleBooking(page) {
  await page.goto(`${BASE_URL}/home/products`);

  const firstVehicleCard = page.locator('.vehicle-card').first();
  await expect(firstVehicleCard).toBeVisible();

  await firstVehicleCard.click();

  await expect(page).toHaveURL(/home\/booking/);
}

async function fillCommonBookingData(page, customerType) {
  const unique = Date.now();

  await page.locator('[name="nama_cust"]').fill(`${customerType} Test ${unique}`);
  await page.locator('[name="no_tlp"]').fill('081234567890');
  await page.locator('[name="country_origin"]').fill(customerType === 'WNI' ? 'Indonesia' : 'Australia');
  await page.locator('[name="alamat"]').fill('Denpasar, Bali');

  // Field tipe_customer berbentuk radio button, bukan select
  await page
    .locator(`input[name="tipe_customer"][value="${customerType}"]`)
    .check({ force: true });

  // Beri waktu sebentar agar form dokumen WNI/WNA berubah
  await page.waitForTimeout(300);

  await page.locator('[name="tgl_pinjam"]').fill(dateInputValue(1));
  await page.locator('[name="tgl_kembali"]').fill(dateInputValue(3));

  await page.locator('[name="jam_pengambilan"]').selectOption({ index: 1 });
  await page.locator('[name="jam_pengembalian"]').selectOption({ index: 2 });

  const pickupMethod = page.locator('input[name="metode_pengambilan"][value="ambil_sendiri"]');
  if (await pickupMethod.count()) {
    await pickupMethod.check({ force: true });
  }

  const paymentMethod = page.locator('input[name="metode_pembayaran"][value="transfer"]');
  if (await paymentMethod.count()) {
    await paymentMethod.check({ force: true });
  }
}

// Helper upload file hanya ke input yang aktif / tidak disabled
async function uploadEnabledFile(page, inputName, filePath) {
  const input = page.locator(`input[name="${inputName}"]:not([disabled])`);

  await expect(input).toHaveCount(1);
  await input.setInputFiles(filePath);
}

test.describe('Black-box Testing - Booking User WNI/WNA', () => {
  test('User WNI berhasil melakukan booking dan masuk halaman sukses', async ({ page }) => {
    await openFirstVehicleBooking(page);

    await fillCommonBookingData(page, 'WNI');

    // Untuk WNI, upload KTP / identity card yang aktif saja
    await uploadEnabledFile(page, 'foto_ktp', files.ktp);

    await page.locator('form button[type="submit"]').click();

    await expect(page.getByText(/Booking Successful/i)).toBeVisible();
    await expect(page.getByText(/Download Invoice/i)).toBeVisible();
  });

  test('User WNA berhasil melakukan booking dengan 4 dokumen dan masuk halaman sukses', async ({ page }) => {
    await openFirstVehicleBooking(page);

    await fillCommonBookingData(page, 'WNA');

    // Untuk WNA, upload semua dokumen yang aktif
    await uploadEnabledFile(page, 'foto_sim', files.sim);
    await uploadEnabledFile(page, 'foto_ktp', files.ktp);
    await uploadEnabledFile(page, 'foto_tiket', files.tiket);
    await uploadEnabledFile(page, 'foto_hotel', files.hotel);

    await page.locator('form button[type="submit"]').click();

    await expect(page.getByText(/Booking Successful/i)).toBeVisible();
    await expect(page.getByText(/Download Invoice/i)).toBeVisible();
  });
});