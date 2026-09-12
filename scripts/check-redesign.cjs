const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

// Successful writes are intercepted; this smoke check never creates live records.
(async () => {
  const base = process.env.AROGIO_TEST_URL || 'http://127.0.0.1:8011';
  const output = path.resolve('storage/app/redesign-check');
  fs.mkdirSync(output, { recursive: true });
  const browser = await chromium.launch({ headless: true });
  try {
    const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.goto(base, { waitUntil: 'networkidle' });
    await page.getByRole('heading', { name: 'Find the right care, closer to you.' }).waitFor();
    await page.locator('#arogio-home .MuiCardActionArea-root').first().waitFor();
    assert(await page.locator('.MuiCardActionArea-root').count() >= 6);
    assert.equal(await page.locator('#compare-dock').isVisible(), false);
    assert.equal(await page.locator('#lead-capture-modal').isVisible(), false);
    await page.getByRole('button', { name: 'Find Care', exact: true }).click();
    await page.getByRole('menuitem', { name: 'Hospitals & Clinics' }).waitFor();
    await page.keyboard.press('Escape');

    await page.route('**/api/search?**', route => route.fulfill({ json: { doctors: [], matched_department: null } }));
    await page.getByRole('textbox', { name: 'Specialty, doctor, clinic, or hospital' }).fill('Cardiology');
    await page.getByRole('button', { name: 'Search Directory', exact: true }).click();
    await page.getByText('No matching records found.', { exact: false }).waitFor();
    await page.getByRole('button', { name: 'Close results' }).click();
    await page.unroute('**/api/search?**');
    await page.route('**/api/search?**', route => route.fulfill({ status: 503, json: { message: 'Unavailable' } }));
    await page.getByRole('button', { name: 'Search Directory', exact: true }).click();
    await page.getByText('Search is unavailable.', { exact: false }).waitFor();
    await page.unroute('**/api/search?**');

    await page.getByRole('button', { name: 'Ask Jeeva', exact: true }).click();
    await page.locator('#chatbot-input').waitFor({ state: 'visible' });
    await page.getByRole('button', { name: 'Close AI assistant' }).click();

    const form = page.locator('form[action$="consultation-requests"]');
    await form.getByLabel('Full name').fill('UI Verification');
    await form.getByLabel('Email address').fill('verification@example.invalid');
    await form.getByLabel('Phone number').fill('9999999999');
    await form.getByLabel('Preferred date').fill('2099-01-01');
    await form.getByLabel('Preferred time').fill('10:30');
    await form.getByLabel('Reason for consultation').fill('UI verification of the consultation request form.');
    let submitted = false;
    await page.route('**/consultation-requests', async route => {
      assert(route.request().headers()['x-csrf-token']);
      assert(route.request().postData().includes('UI Verification'));
      submitted = true;
      await route.fulfill({ json: { success: true } });
    });
    await form.getByRole('button', { name: 'Submit consultation request' }).click();
    await form.getByText('Your consultation request has been received.', { exact: false }).waitFor();
    assert(submitted);
    await page.unroute('**/consultation-requests');

    await page.getByRole('button', { name: 'Toggle color theme' }).click();
    assert(await page.locator('html').evaluate(el => el.classList.contains('dark')));
    await page.screenshot({ path: path.join(output, 'desktop-dark.png') });
    await page.getByRole('button', { name: 'Toggle color theme' }).click();

    for (const width of [1440, 768, 390]) {
      await page.setViewportSize({ width, height: 900 });
      await page.evaluate(() => scrollTo(0, 0));
      assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), `Horizontal overflow at ${width}px`);
      await page.screenshot({ path: path.join(output, `home-${width}.png`), fullPage: true });
    }
    await page.getByRole('button', { name: 'Open navigation menu' }).click();
    await page.getByRole('button', { name: 'Close navigation', exact: true }).click();
    await page.getByRole('button', { name: 'Jaipur', exact: true }).click();
    await page.getByRole('textbox', { name: 'City', exact: true }).fill('Delhi');
    await page.getByRole('button', { name: 'Use this city' }).click();
    await page.getByRole('button', { name: 'Delhi', exact: true }).waitFor();
    await page.getByRole('link', { name: /Find Doctors.*Browse doctors/ }).click();
    assert(new URL(page.url()).searchParams.get('city[]') === 'Delhi');
    await page.goto(base, { waitUntil: 'networkidle' });
    await page.getByRole('button', { name: 'हिंदी में बदलें' }).click();
    await page.getByRole('heading', { name: 'सही स्वास्थ्य सेवा खोजें, अपने आस-पास।' }).waitFor();
    assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth));
    await page.screenshot({ path: path.join(output, 'home-hindi.png'), fullPage: true });
    await page.getByRole('button', { name: 'Switch to English' }).click();

    for (const route of ['/doctors', '/hospitals', '/blood-banks', '/medicines', '/articles', '/activities', '/nani-dadi-ke-nuskhe', '/consultations', '/contact']) {
      const response = await page.goto(base + route, { waitUntil: 'networkidle' });
      assert.equal(response.status(), 200, route);
      await page.locator('#arogio-header .MuiButton-root:visible').first().waitFor();
    }
    assert.deepEqual(errors, []);
    console.log('Passed: responsive layouts, menus, search states, Jeeva, consultation form wiring, city routing, Hindi, dark theme, and 9 public pages.');
    console.log(`Screenshots: ${output}`);
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exit(1); });
