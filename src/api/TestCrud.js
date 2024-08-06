import axios from 'axios';

const TestCrud = {};

const nonce = appLocalizer.nonce;

TestCrud.index = async (params = null) => {
    const url = '/wp-json/test-plugin-crud/v1/datas';
    try {
        const response = await axios.get(url, { params: params });
        return response.data;
    } catch (error) {
        console.error(error);
        return [];
    }
};

TestCrud.show = async (id, params = null) => {
    const url = '/wp-json/test-plugin-crud/v1/get-data/' + id;
    try {
        const response = await axios.get(url, { params: params });
        return response.data;
    } catch (error) {
        console.error(error);
        return [];
    }
};

TestCrud.delete = async (id, params = null) => {
    const url = '/wp-json/test-plugin-crud/v1/delete-data/' + id;
    try {
        const response = await axios.get(url, { params: params });
        return response.data;
    } catch (error) {
        console.error(error);
        return [];
    }
};

TestCrud.save = async (data) => {
    let url = "/wp-json/test-plugin-crud/v1/add-data";
    const res = await axios.post(url, data, {
        headers: {
            'X-WP-Nonce': nonce
            }
        }).then(response => {
            return response.data;
        }).catch(error => { return []; });
    return res;
}

export default TestCrud;