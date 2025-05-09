export class AWTRespondRequest {

    constructor(baseURL, defaultHeaders = {}) {
        this.baseURL = baseURL;
        this.defaultHeaders = {
            'Content-Type': 'application/json', // Default header
            ...defaultHeaders // Override or add additional headers
        };

        this.strict = false;
    }

    setStrict() {
        this.strict = true;
    }

    async sendRequest(endpoint, method = 'GET', body = null, customHeaders = {}) {
        const headers = { ...this.defaultHeaders, ...customHeaders };
        const requestOptions = {
            method: method,
            headers: headers,
        };

        if (body) {
            requestOptions.body = JSON.stringify(body);
        }


        try {
            console.log('Request Headers:', requestOptions);
            const response = await fetch(`${this.baseURL}${endpoint}`, requestOptions);
            if (!response.ok && this.strict) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Request failed:', error);
            throw error;
        }
    }
    get(endpoint, customHeaders = {}) {
        return this.sendRequest(endpoint, 'GET', null, customHeaders);
    }
    post(endpoint, body, customHeaders) {
        return this.sendRequest(endpoint, 'POST', body, customHeaders);
    }
    put(endpoint, body, customHeaders = {}) {
        return this.sendRequest(endpoint, 'PUT', body, customHeaders);
    }

    delete(endpoint, customHeaders = {}) {
        return this.sendRequest(endpoint, 'DELETE', null, customHeaders);
    }
}
