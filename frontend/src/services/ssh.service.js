import RequestHelper from "@/services/RequestHelper";

export default class SshService {

    async execute(connection_id, command,terminal_id) {
        let request = new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/ssh/execute/' + connection_id , {_method: 'PUT',command:command,terminal_id:terminal_id});
    }


    async checkStatus(execution_id) {
        let request = new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/ssh/status/' + execution_id , {});
    }
}
