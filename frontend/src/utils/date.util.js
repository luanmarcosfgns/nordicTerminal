export  default  class DateUtil {
     format(data) {
        const dataObj = new Date(data);
        const ano = dataObj.getFullYear();
        const mes = (dataObj.getMonth() + 1).toString().padStart(2, '0');
        const dia = dataObj.getDate().toString().padStart(2, '0');
        const horas = dataObj.getHours().toString().padStart(2, '0');
        const minutos = dataObj.getMinutes().toString().padStart(2, '0');
        const segundos = dataObj.getSeconds().toString().padStart(2, '0');

        // Formato desejado: dd/mm/yyyy hh:mm:ss
        const dataFormatada = `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;

        return dataFormatada;
    }
}