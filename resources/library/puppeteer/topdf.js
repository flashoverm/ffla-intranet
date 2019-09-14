const puppeteer = require('puppeteer');

(async() => {

	try {
		const browser = await puppeteer.launch({
			headless: true,
			args: [
				'--no-sandbox'
			]
		});
		const page = await browser.newPage();
		process.on("unhandledRejection", (reason, p) => {
			console.error("Unhandled Rejection at: Promise", p, "reason:", reason);
			browser.close();
		});
		await page.goto(process.argv[2], {
			timeout: 12000,
			waitUntil: 'networkidle2'
		});

		
		page.addStyleTag({
			'content': '@page {size: auto}'
		});
		
		await page.pdf({
		    margin: {
		        top: "1cm",
		        right: "1cm",
		        bottom: "1cm",
		        left: "1cm"
		    },
			path: process.argv[3], 
			format: 'A4',
			landscape: true
		});
		
		await browser.close();
		
		console.log("Done");
	} catch (e) {
		console.log(e);
		browser.close();
	}

})();
