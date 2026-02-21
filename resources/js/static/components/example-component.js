// This is an example on how a very simple component can look like
// one input value, one setter and one data value
// for TypeScript support look at the other more complex components
export default (inputValue) => ({
    currentValue: inputValue,

    setValue(url) {
        this.currentValue = url;
    },
});
