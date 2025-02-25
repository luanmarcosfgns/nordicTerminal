export default class MaskTollBox {
    constructor(mask, value, length = false) {
        this.mask = mask;
        this.value = String(value); // Garantir que value seja uma string
        this.value = this.value.replaceAll(/[^a-zA-Z0-9\s]/g, '');
        this.value = this.value.replaceAll(' ', '');
        console.log(this.value)
        this.length = length ? parseInt(length, 10) : false; // Converter length para inteiro se existir

        // Aplicar a máscara se o valor for fornecido
        this.applyMask();
    }

    applyMask() {
        let result = '';
        let valueIndex = 0;
        if( this.value.length==0){
            return '';
        }
        // Iterar sobre cada caractere da máscara
        for (let i = 0; i < this.mask.length; i++) {
            const maskChar = this.mask[i];
            const valueChar = this.value[valueIndex];


            // Verificar se a máscara contém um placeholder para caracteres
            if (maskChar === 'A' || maskChar === '9' || maskChar === 'Z' || maskChar === 'a' || maskChar === '0') {
                // Verificar se o caractere atual do valor corresponde ao tipo de placeholder na máscara
                if ((maskChar === 'A' && valueChar.match(/[A-Z]/)) ||
                    (maskChar === '9' && valueChar.match(/[0-9]/)) ||
                    (maskChar === 'Z' && valueChar.match(/[A-Za-z]/)) ||
                    (maskChar === 'a' && valueChar.match(/[a-z]/)) ||
                    (maskChar === '0' && valueChar.match(/[0-9a-zA-Z]/))) {
                    result += valueChar;
                    valueIndex++;
                }
            } else {
                // Se não for um placeholder, apenas adicione o caractere da máscara
                result += maskChar;
            }

            // Parar o loop se já passamos pelo último caractere do valor
            if (valueIndex === this.value.length) break;
        }

        // Se o comprimento for especificado, cortar a string para esse comprimento
        if (this.length !== false) {
            result = result.substring(0, this.length);
        }
        // Retornar o resultado final
        return result;
    }
}

