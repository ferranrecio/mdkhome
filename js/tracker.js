

class Tracker {

    constructor() {
        this.url = document.body.dataset.trackerhelper; // "http://localhost/tracker.php";
        this.wwwpix = document.body.dataset.wwwpix;

        this.selectors = {
            MDL: `[data-mdl]`,
            BRANCH: `[data-tracker="branch"]`,
            TITLE: `[data-tracker="title"]`,
            ICON: `[data-tracker="icon"]`,
            URL: `[data-tracker="url"]`,
            STATUS: `[data-tracker="status"]`,
            REFRESH: `[data-tracker="refresh"]`,
        };

        if (window.location.hostname === 'localhost') {
            this.scanInfo();
        }
    }

    async makeRequest(method, url, data) {

        const params = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'mode': 'cors',
            }
        };

        if (data !== undefined) {
            params.body = JSON.stringify(data);
        }

        const response = await fetch(
            url,
            params
        );

        return await response.json(); //extract JSON from the http response
    }

    async getMDLinfo(mdlCode) {
        let url = `${this.url}?mdl=${mdlCode}`;
        try {
            let info = this.getMDLinfoCache(mdlCode);
            if (info) {
                return info
            }
            info = await this.makeRequest('GET', url);
            this.setMDLinfoCache(mdlCode, info);
            return info
        } catch(error) {
            console.log(error);
        }
        return false;
    }

    getMDLinfoCache(mdlCode) {
        const info = localStorage.getItem(`tracker/${mdlCode}`);
        if (info) {
            return JSON.parse(info);
        }
        return false;
    }

    setMDLinfoCache(mdlCode, info) {
        localStorage.setItem(`tracker/${mdlCode}`, JSON.stringify(info));
    }

    removeMDLinfoCache(mdlCode) {
        localStorage.removeItem(`tracker/${mdlCode}`);
    }

    scanInfo() {
        // Get elements with data-mdl.
        const elements = document.querySelectorAll(this.selectors.MDL);
        for (const element of elements) {
            if (!element.dataset.mdl) {
                continue;
            }

            this.refreshElementInfo(element);
            // Add double click for refresh tracker info.
            element.addEventListener('dblclick', () => {
                this.removeMDLinfoCache(element.dataset.mdl);
                this.refreshElementInfo(element);
            });
        }

        // Add double click to title.
        const refresh = document.querySelector(this.selectors.REFRESH);
        if (refresh) {
            refresh.addEventListener('dblclick', () => {
                this.refreshAll();
            });
        }
    }

    refreshAll() {
        const elements = document.querySelectorAll(this.selectors.MDL);
        for (const element of elements) {
            if (!element.dataset.mdl) {
                continue;
            }
            this.removeMDLinfoCache(element.dataset.mdl);
            this.refreshElementInfo(element);
        }
    }

    async refreshElementInfo(element) {
        element.classList.add('loading');
        element.querySelector(this.selectors.ICON).src = this.wwwpix + '/help.gif';
        // Get tracker info.
        const info = await this.getMDLinfo(element.dataset.mdl);
        if (!info) {
            return;
        }
        // Replace elements.
        element.querySelector(this.selectors.ICON).src = info.icon;
        element.querySelector(this.selectors.TITLE).innerText = info.title;
        element.querySelector(this.selectors.URL).href = info.url;
        element.querySelector(this.selectors.STATUS).title = info.status;
        element.querySelector(this.selectors.BRANCH).classList.remove('d-none');

        element.classList.toggle('closed', info.status == 'Closed');
        element.classList.toggle('inprogress', info.status == 'Development in progress');

        const waitingIntegration = (
            info.status == 'Waiting for component lead review'
            || info.status == 'Component lead review in progress'
            || info.status == 'Waiting for integration review'
            || info.status == 'Integration review in progress'
        );
        element.classList.toggle('waiting-integration', waitingIntegration);

        setTimeout(
            () => {
                element.classList.remove('loading');
            },
            250
        );
    }

}

const tracker = new Tracker();
