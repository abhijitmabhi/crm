const statistics = {
    keywords: {
        keywords: [],
        statistics: {},
        activeKeyword: null,
        async fetchKeywords(companyId, locationId) {
            const response = await axios.get(
                `/api/companies/${companyId}/locations/${locationId}/keywords/`
            );
            this.keywords = response.data;
        },
        async addKeyword(companyId, locationId, keyword) {
            await axios.post(`/api/companies/${companyId}/locations/${locationId}/keywords`, {
                keyword,
            });
            await this.fetchKeywords(companyId, locationId);
        },
        async removeKeyword(companyId, locationId, id) {
            await axios.delete(
                `/api/companies/${companyId}/locations/${locationId}/keywords/${id}`
            );
            await this.fetchKeywords(companyId, locationId);
        },
        async fetchKeywordStatistics(companyId, locationId, keyword, startDate, endDate) {
            const response = await axios.get(
                `/api/companies/${companyId}/locations/${locationId}/statistics`,
                { params: { include: ["keywords"], startDate, endDate, keyword } }
            );
            this.statistics[locationId] = response.data;
        },
    },
};

export { statistics };
