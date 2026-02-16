export default (inputValue) => ({
    currentValue: inputValue,

    setValue(url) {
        this.currentValue = url;
    },
});
