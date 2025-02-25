export default class Helpers{

    empty(data){
        if(data==null || data == '' || data=='undefined' || data==undefined || data==0 || data==false ){
            return true;
        }
        return false;
    }
    isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
     decodeUnicode(inputString) {
        return inputString.replace(/\\u([0-9a-fA-F]{4})/g, (match, hex) => {
            return String.fromCharCode(parseInt(hex, 16));
        });
    }
    numberFormat(value,decimal =2,simbolo = '.'){
        if(simbolo=='.'){
            value =  new String(value).replace(',','.')
           return  parseFloat(value).toFixed(decimal);
        }
        if(simbolo==','){
            return new String(value.toFixed(decimal)).replace('.',',')
        }

    }
    unmaskNumber(value){
        return value.replace(/[^0-9]/g, '');
    }


}