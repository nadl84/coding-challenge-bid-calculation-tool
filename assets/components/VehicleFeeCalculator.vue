<template>
  <div class="vehicle-fee-calculator">
    <div class="form-group mb-3">
      <label for="basePrice" class="form-label">Vehicle Base Price</label>
      <input
        type="number"
        id="basePrice"
        v-model="basePrice"
        class="form-control"
        step="0.01"
        min="0"
        @input="calculateFees"
      >
    </div>

    <div class="form-group mb-3">
      <label for="vehicleType" class="form-label">Vehicle Type</label>
      <select
        id="vehicleType"
        v-model="vehicleType"
        class="form-select"
        @change="calculateFees"
      >
        <option value="common">Common</option>
        <option value="luxury">Luxury</option>
      </select>
    </div>

    <div v-if="fees.length > 0" class="fees-summary mt-4">
      <h3>Fee Breakdown</h3>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Description</th>
              <th class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Base Price</td>
              <td class="text-end">{{ formatPrice(basePrice) }}</td>
            </tr>
            <tr v-for="fee in fees" :key="fee.name">
              <td>{{ fee.name }}</td>
              <td class="text-end">{{ formatPrice(fee.amount) }}</td>
            </tr>
            <tr class="table-active fw-bold">
              <td>Total</td>
              <td class="text-end">{{ formatPrice(total) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="error" class="alert alert-danger mt-3" role="alert">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import debounce from 'lodash/debounce'

export default {
  name: 'VehicleFeeCalculator',
  setup() {
    const basePrice = ref(0)
    const vehicleType = ref('common')
    const fees = ref([])
    const total = ref(0)
    const error = ref('')

    const formatPrice = (price) => {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(price)
    }

    const calculateFees = debounce(async () => {
      if (basePrice.value <= 0) {
        fees.value = []
        total.value = 0
        return
      }

      try {
        // Create FormData object
        const formData = new FormData()
        formData.append('basePrice', basePrice.value)
        formData.append('type', vehicleType.value)

        const response = await axios.post('/api/vehicles/fees/calculate', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        fees.value = response.data.fees
        total.value = response.data.total
        error.value = ''
      } catch (e) {
        error.value = e.response?.data?.errors || 'An error occurred while calculating fees'
        fees.value = []
        total.value = 0
      }
    }, 300)

    onMounted(() => {
      calculateFees()
    })

    return {
      basePrice,
      vehicleType,
      fees,
      total,
      error,
      calculateFees,
      formatPrice
    }
  }
}
</script>

<style scoped>
.vehicle-fee-calculator {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.fees-summary {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
}

.table th, .table td {
  padding: 12px;
}
</style> 