const dc = require("./dc");

(async () => {
  try {
    await dc.initialize();
    await dc.login();
    await dc.openChannel();
  } catch (error) {
    console.error(error.message);
    if (dc.browser) {
      await dc.browser.close();
    }
    process.exitCode = 1;
  }
})();
