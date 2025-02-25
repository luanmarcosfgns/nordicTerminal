import axios from "axios";


export default class RequestHelper {
    constructor() {
    }

   async  postAuth(url, data) {

        await axios.post(url, data, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("HASH")}`,
                Accept: 'application/json',
                "Content-Type": 'application/json'
            },
        })
            .then((response) => {
                window.axios = response;
            }).catch((error) => {
                this.invalidHash(error);
                window.axios = error;
        });
        return  window.axios;

    }
   async  post(url, data) {

        await axios.post(url, data, {
            headers: {
                Accept: 'application/json',
                "Content-Type": 'application/json'
            },
        })
            .then((response) => {
                window.axios = response;
            }).catch((error) => {
                this.invalidHash(error);
                window.axios = error;
        });
        return  window.axios;

    }

    async getAuth(url, dataParams) {
        let params = '';
        let keys  = Object.keys(dataParams);
        let values = Object.values(dataParams);
        let lenghArray = keys.length;
        if(lenghArray>0){
            params = '?';
        }
        for (let i = 0;i<lenghArray;i++ ){
            params += keys[i]+'='+values[i];
            if((i+1)<lenghArray){
                params +='&';
            }
        }

        await axios.get(url+params, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("HASH")}`,
                Accept: 'application/json',
                "Content-Type": 'application/json'
            }
        })
            .then((response) => {
                window.axios = response;
            }).catch((error) => {
                this.invalidHash(error);
                window.axios = error;
            });
        return  window.axios;
    }
    async get(url, dataParams) {
        let params = '';
        let keys  = Object.keys(dataParams);
        let values = Object.values(dataParams);
        let lenghArray = keys.length;
        if(lenghArray>0){
            params = '?';
        }
        for (let i = 0;i<lenghArray;i++ ){
            params += keys[i]+'='+values[i];
            if((i+1)<lenghArray){
                params +='&';
            }
        }

        await axios.get(url+params, {
            headers: {
                Accept: 'application/json',
                "Content-Type": 'application/json'
            }
        })
            .then((response) => {
                window.axios = response;
            }).catch((error) => {
                this.invalidHash(error);
                window.axios = error;
            });
        return  window.axios;
    }

    async deleteAuth(url) {

        await axios.delete(url, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("HASH")}`,
                Accept: 'application/json',
                "Content-Type": 'application/json'
            }
        })
            .then((response) => {
                window.axios = response;
            }).catch((error) => {
                this.invalidHash(error);
                window.axios = error;
            });
        return  window.axios;

    }
    invalidHash(error){
        if (error?.request?.status == 403 || error?.request?.status == 401) {
            window.axios = false;
            localStorage.removeItem('HASH');
            location.href = '/login'

        }
    }
}

