export const url = window.your_plugin_name.ajax_url
export const securityToken = window.your_plugin_name.security_token


/**
 * Generates the AJAX URL for a given action.
 *
 * @param {string} action - The action to be performed.
 * @return {string} The generated AJAX URL.
 */
export const ajaxUrl = (action) => {
    return `${url}?security_token=${securityToken}&action=your-plugin-name-${action}`;
}


/**
 * Returns an object with functions to interact with the browser's local storage.
 *
 * @return {Object} An object with the following methods:
 * - `save(key, data)`: Saves `data` to local storage with the given `key`.
 * - `get(key)`: Retrieves data from local storage with the given `key`.
 * - `remove(key)`: Removes data from local storage with the given `key`.
 * - `hasNew(data)`: Checks if `data` is different from the data stored with the same key.
 */
export const useStorage = () => {
    const prefix = "your-plugin-name";

    const getKey = (key) => `${prefix}-${key}`;
    const hasNew = (key, data) => {
        const oldItems = get(getKey(key));
        return oldItems !== JSON.stringify(data);
    };
    const save = (key, data) => {
        if (hasNew(key, data)) {
            localStorage.setItem(getKey(key), JSON.stringify(data));
        }
    };
    const get = (key) => {
        const data = localStorage.getItem(getKey(key));
        return data ? JSON.parse(data) : null;
    };

    const remove = (key) => {
        localStorage.removeItem(getKey(key));
    }

    const removeAll = () => {
        localStorage.clear();
        location.reload();
    }

    return {
        save,
        get,
        remove,
        removeAll,
        hasNew
    };
};



/**
 * Fetches data from the server based on the provided action and stores it in the specified location.
 *
 * @param {string} action - The action to be performed on the server.
 * @param {string} storeKey - The key to store the fetched data in.
 * @return {Promise<any>} The fetched data.
 */
export const fetchData = async (action, storeKey) => {
    let response = await fetch(ajaxUrl(action));
    if (!response) return;
    let data = await response.json();
    if (data.success) {
        useStorage().save(storeKey, data.data);
        return data.data;
    } else {
        if (data.message) {
            console.error(data.message);
        }
        return;
    }
};

/**
 * Sends data to the server using AJAX.
 *
 * @param {string} action - The action to be performed on the server.
 * @param request
 * @param {string} storageKey - The key to store the returned data in local storage.
 * @return {Promise<any>} A promise that resolves to the returned data from the server.
 */
export const sendData = async (action, request, storageKey) => {
    const headers = { 'Content-Type': 'application/json' };
    const requestBody = JSON.stringify(request);

    let response = await fetch(ajaxUrl(action), { method: 'POST', headers, body: requestBody });
    let data = await response.json();
    if (data.success) {
        useStorage().save(storageKey, request);
        return data.data;
    } else {
        console.error(data.data.message);
    }
}
