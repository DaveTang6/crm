const configDev = {
	apiURL: 'http://127.0.0.1/kkcrm/kkcrmio/',
	updateURL: 'http://127.0.0.1/kkcrm/kkcrmio/Commons/updateFile',
	filePath: 'http://127.0.0.1/kkcrm/kkcrmio/files/',
	payStatus: {
		0: 'Not Approved',
		1: 'Approved'
	},
	orderType: {
		0: 'Deposit',
		1: 'Full Payment'
	},
	settleStatus: {
		0: 'Unsettled',
		1: 'Settled'
	}
}

const configPrd = {
	apiURL: 'http://kkcrm.saillingtech.com:8721/io/',
	updateURL: 'http://kkcrm.saillingtech.com:8721/io/Commons/updateFile',
	filePath: 'http://kkcrm.saillingtech.com:8721/io/files/',
	payStatus: {
		0: 'Not Approved',
		1: 'Approved'
	},
	orderType: {
		0: 'Deposit',
		1: 'Full Payment'
	},
	settleStatus: {
		0: 'Unsettled',
		1: 'Settled'
	}
}

const config = (key) => {
	if (process.env.NODE_ENV === 'development') {
		return configDev[key]
	} else {
		return configPrd[key]
	}
}

export default config
