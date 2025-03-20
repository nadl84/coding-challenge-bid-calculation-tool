import { createApp } from 'vue'
import VehicleFeeCalculator from './components/VehicleFeeCalculator.vue'
// Import Bootstrap CSS and JS
import 'bootstrap'
import './styles/app.scss'  // We'll create this file

const app = createApp({
    components: {
        'vehicle-fee-calculator': VehicleFeeCalculator
    },
    template: '<vehicle-fee-calculator />'
})

app.mount('#app') 