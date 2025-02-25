export default class HelperTollBox {
    empty(value) {
        if (value === '' || value === undefined || isNaN(value) || value === '' || value === null) return true;
        return false;

    }
}