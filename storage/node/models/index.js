require('dotenv').config({path: './../../../.env'})
const fs = require('fs')
const path = require('path')
const Sequelize = require('sequelize')
const basename = path.basename(__filename)
const db = {}

let sequelize = new Sequelize(process.env.DB_DATABASE, process.env.DB_USERNAME, process.env.DB_PASSWORD, {
  host: process.env.DB_HOST,
  port: process.env.DB_PORT,
  dialect: 'postgres',
  logging: false,
  timezone: process.env.APP_TIMEZONE,
  dialectOptions: {
    useUTC: false
  },
  reconnect: {
    max_retries: 1,
    onRetry: (retry) => {
      console.log(`Conexión con base de datos perdida, intentando por ${retry} vez`);
      process.exit(1);
    }
  }
})

fs.readdirSync(__dirname).filter(file => {
  return (file.indexOf('.') !== 0) && (file !== basename) && (file.slice(-3) === '.js')
}).forEach(file => {
  let model = sequelize['import'](path.join(__dirname, file))
  db[model.name] = model
})

Object.keys(db).forEach(modelName => {
  if (db[modelName].associate) {
    db[modelName].associate(db)
  }
})

async function testConnection() {
  try {
    await sequelize.authenticate()
    console.log('Database connection successfully')
  } catch (e) {
    console.error('Database connection error')
    console.error(e)
  }
}

testConnection()

db.sequelize = sequelize
db.Sequelize = Sequelize

module.exports = db