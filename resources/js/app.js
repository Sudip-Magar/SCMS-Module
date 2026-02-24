import './bootstrap';

window.DateSync = {

    bsToAd(bsDate) {
        const ad = NepaliFunctions.BS2AD(bsDate);
        return `${ad.year}-${String(ad.month).padStart(2, '0')}-${String(ad.day).padStart(2, '0')}`;
    },

    adToBs(adDate) {
        const [y, m, d] = adDate.split("-");
        const bs = NepaliFunctions.AD2BS({ year: +y, month: +m, day: +d });
        return `${bs.year}-${String(bs.month).padStart(2, '0')}-${String(bs.day).padStart(2, '0')}`;
    },

    attach(nepali, english) {

        if (nepali.dataset.synced) return;

        nepali.NepaliDatePicker({
            miniEnglishDates: true,
            // container: ".open-drawer",
            language: "english",
            onSelect: (bs) => {
                english.value = this.bsToAd(bs);
                nepali.dispatchEvent(new Event('input'));
                english.dispatchEvent(new Event('input'));

            }
        });

        english.addEventListener("change", () => {
            if (!english.value) return;

            const bsString = this.adToBs(english.value);
            nepali.value = bsString;
            english.dispatchEvent(new Event('input'));
            nepali.dispatchEvent(new Event('input'));



            const interval = setInterval(() => {
                const [year, month, day] = bsString.split("-").map(Number);
                if (nepali._nepaliDatePicker) {
                    nepali._nepaliDatePicker.onSelect({ year, month, day }); // trigger picker selection
                    clearInterval(interval);
                }
            }, 20);
        });

        nepali.dataset.synced = true;
    }
};
