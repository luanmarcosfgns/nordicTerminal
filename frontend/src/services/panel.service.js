import RequestHelper from "@/services/RequestHelper";


export default class PanelService {
    async panelCount(){
        let requestHelper = new RequestHelper();
        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/panel', {});
    }
    async listVendas(){
        let requestHelper = new RequestHelper();
        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/panel/vendas', {});
    }
}