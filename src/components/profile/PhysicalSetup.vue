<script setup>
import { ref } from 'vue'
// import { useUserStore } from '@/stores/user'
import BaseButton from '@/components/ui/BaseButton.vue'

// const userStore = useUserStore()
const height = ref('')
const weight = ref('')
const age = ref('')
// const emit = defineEmits(['complete'])

const savePhysicalParams = () => {
  // if (height.value && weight.value && age.value) {
  //   userStore.setPhysicalParams({
  //     height: parseInt(height.value),
  //     weight: parseInt(weight.value),
  //     age: parseInt(age.value)
  //   })
  //   emit('complete')
  // }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full">
      <h2 class="text-2xl font-bold mb-6">Настройка параметров</h2>

      <div class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Возраст
          </label>
          <input v-model="age" type="number" min="14" max="100"
            class="w-full  py-2 rounded-xl border border-gray-200 focus:border-violet-600 focus:ring-1 focus:ring-violet-600 outline-none"
            placeholder="Введите ваш возраст">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Рост (см)
          </label>
          <input v-model="height" type="number" min="140" max="250"
            class="w-full  py-2 rounded-xl border border-gray-200 focus:border-violet-600 focus:ring-1 focus:ring-violet-600 outline-none"
            placeholder="Введите ваш рост">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Вес (кг)
          </label>
          <input v-model="weight" type="number" min="40" max="200"
            class="w-full  py-2 rounded-xl border border-gray-200 focus:border-violet-600 focus:ring-1 focus:ring-violet-600 outline-none"
            placeholder="Введите ваш вес">
        </div>

        <BaseButton class="w-full" @click="savePhysicalParams" :disabled="!height || !weight || !age">
          Продолжить
        </BaseButton>
      </div>
    </div>
  </div>
</template>