const configDev = {
	apiURL: 'http://127.0.0.1/kkcrm/kkcrmio/',
	updateURL: 'http://127.0.0.1/kkcrm/kkcrmio/Commons/updateFile',
	filePath: 'http://127.0.0.1/kkcrm/kkcrmio/files/',
	payStatus: {
		0: '未通过',
		1: '已通过'
	},
	orderType: {
		0: '定金',
		1: '全款'
	},
	settleStatus: {
		0: '未结算',
		1: '已结算'
	}
}

const configPrd = {
	apiURL: 'http://kkcrm.saillingtech.com:8721/io/',
	updateURL: 'http://kkcrm.saillingtech.com:8721/io/Commons/updateFile',
	filePath: 'http://kkcrm.saillingtech.com:8721/io/files/',
	payStatus: {
		0: '未通过',
		1: '已通过'
	},
	orderType: {
		0: '定金',
		1: '全款'
	},
	settleStatus: {
		0: '未结算',
		1: '已结算'
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
