const path = require("path");
require("dotenv").config({ path: path.join(__dirname, ".env") });

const puppeteer = require("puppeteer");

const BASE_URL = "https://discord.com";
const CHANNEL_URL = process.env.DISCORD_CHANNEL_URL;
const PROFILE_DIR = path.join(__dirname, ".discord-profile");

const discord = {
  browser: null,
  page: null,
  initialize: async () => {
    discord.browser = await puppeteer.launch({
      headless: false,
      userDataDir: PROFILE_DIR,
      defaultViewport: null,
    });
    discord.page = await discord.browser.newPage();

    await discord.page.goto(`${BASE_URL}/app`, {
      waitUntil: "domcontentloaded",
    });
  },
  login: async () => {
    const email = process.env.DISCORD_EMAIL;
    const password = process.env.DISCORD_PASSWORD;

    if (!email || !password) {
      throw new Error(
        "DISCORD_EMAIL dan DISCORD_PASSWORD wajib diisi di file .env.",
      );
    }

    const emailSelector =
      'input[name="email"], input[type="email"], input[autocomplete="username"]';
    const passwordSelector = 'input[name="password"], input[type="password"]';

    try {
      await discord.page.waitForSelector(emailSelector, { timeout: 15000 });
      await discord.page.waitForSelector(passwordSelector, { timeout: 15000 });
    } catch {
      if (!discord.page.url().includes("/login")) {
        return;
      }
      throw new Error(
        `Form login Discord tidak ditemukan. URL saat ini: ${discord.page.url()}`,
      );
    }

    await discord.page.click(emailSelector, { clickCount: 3 });
    await discord.page.type(emailSelector, email);
    await discord.page.click(passwordSelector, { clickCount: 3 });
    await discord.page.type(passwordSelector, password);
    await discord.page.click('button[type="submit"]');

    await discord.page.waitForFunction(
      () => window.location.pathname.startsWith("/channels/"),
      { timeout: 120000 },
    );
  },
  openChannel: async () => {
    if (!CHANNEL_URL) {
      throw new Error(
        "DISCORD_CHANNEL_URL belum diatur. Buat file .env (bukan hanya .env.example) dan isi URL lengkap channel Discord.",
      );
    }

    const channelUrl = new URL(CHANNEL_URL);
    if (
      channelUrl.origin !== BASE_URL ||
      !channelUrl.pathname.startsWith("/channels/")
    ) {
      throw new Error(
        "DISCORD_CHANNEL_URL harus berupa URL channel Discord yang valid.",
      );
    }

    await discord.page.goto(channelUrl.href, { waitUntil: "domcontentloaded" });
    await discord.page.waitForFunction(
      (expectedUrl) => window.location.href.startsWith(expectedUrl),
      { timeout: 120000 },
      channelUrl.href,
    );
    console.log(`Channel terbuka: ${discord.page.url()}`);
  },
};

module.exports = discord;
