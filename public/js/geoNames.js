/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
"use strict";

const geoNames = {
    async getPlaces(gid, src) {
        let element = document.getElementById(src);
        element.innerHTML = '<option value="">Carregando ...</option>';

        gid = gid.split(/_/)[1];
        let uri = `geonames?gid=${ gid }`;

        let response = await axios(uri);
        this.listPlaces(response.data, element);
    },

    listPlaces(response, element) {
        if (!!response.geonames && response.geonames.length > 0) {
            let options = response.geonames.map((geoname) => {
                return `
                    <option
                        value="${ geoname.name }_${geoname.geonameId}">
                    
                        ${ geoname.name }
                    </option>
                `;
            });

            options.splice(0, 0, '<option value="">Escolha ...</option>');

            element.innerHTML = options.join("");
        }
    }
}
