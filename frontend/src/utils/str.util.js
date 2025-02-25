export default class StrUtil {
    truncateString(str, maxLength = 25) {

        if (str.length <= maxLength) {
            return str;
        }
        return str.slice(0, maxLength) + '...';
    }
}