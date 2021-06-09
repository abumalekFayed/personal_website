const date_ranges = [
    {
        text: "اليوم",
        onClick() {
            const date = new Date();
            // return a Date
            return [date, date];
            //return date;
        },
    },
    {
        text: "اليوم الماضي",
        onClick() {
            const date = new Date();
            date.setTime(date.getTime() - 3600 * 1000 * 24);
            return [date, date];
        },
    },

    {
        text: "الاسبوع الحالي",
        onClick() {
            const date = new Date();
            var now = new Date();
            var next_week_start = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate() - (7 - now.getDay())
            );
            return [next_week_start, now];
        },
    },
    {
        text: "الشهر الحالي",
        onClick() {
            const date = new Date();
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            return [firstDay, date];
        },
    },
    {
        text: "العام الحالي",
        onClick() {
            const date = new Date();
            var firstDay = new Date(date.getFullYear(), 0, 1);
            return [firstDay, date];
        },
    },
];

export default date_ranges;
