<script setup>
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/user'
import BaseButton from '@/components/ui/BaseButton.vue'
import { userApi } from '@/api/user'

const userStore = useUserStore()
const showCustomization = ref(false)
const loading = ref(false)
const error = ref(null)
const isEditingName = ref(false)
const newName = ref('')
const isEditingStats = ref(false)
const newHeight = ref('')
const newWeight = ref('')


const fetchUserData = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await userApi.getUserInfo()

    if (response.success) {
      userStore.setUser(response.user)
      newName.value = response.user.name
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при загрузке данных пользователя:', err)
    error.value = 'Не удалось загрузить данные пользователя'
  } finally {
    loading.value = false
  }
}

// Начать редактирование имени
const startEditingName = () => {
  newName.value = userStore.user.name
  isEditingName.value = true
}

// Сохранить новое имя
const saveNewName = async () => {
  if (!newName.value.trim()) {
    return
  }

  loading.value = true
  error.value = null

  try {
    const response = await userApi.updateUser({ name: newName.value.trim() })

    if (response.success) {
      userStore.setUserName(newName.value.trim())
      isEditingName.value = false
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при обновлении имени:', err)
    error.value = 'Не удалось обновить имя'
  } finally {
    loading.value = false
  }
}

// Отменить редактирование имени
const cancelEditingName = () => {
  isEditingName.value = false
  newName.value = userStore.user.name
}

// Сохранение изменений персонажа
const saveCharacterChanges = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await userApi.updateCharacter(userStore.character)

    if (response.success) {
      if (response.character) {
        userStore.setCharacter(response.character)
      }
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при сохранении персонажа:', err)
    error.value = 'Не удалось сохранить изменения'
  } finally {
    loading.value = false
  }
}

const characterParts = {
  hair: [
    { id: 'default', name: 'Обычные', price: 0 },
    { id: 'long', name: 'Длинные', price: 100 },
    { id: 'curly', name: 'Кудрявые', price: 200 },
    { id: 'mohawk', name: 'Ирокез', price: 300 }
  ],
  clothes: [
    { id: 'tshirt', name: 'Футболка', price: 0 },
    { id: 'tank', name: 'Майка', price: 150 },
    { id: 'hoodie', name: 'Худи', price: 250 },
    { id: 'jacket', name: 'Спортивная куртка', price: 400 }
  ],
  accessories: [
    { id: 'none', name: 'Нет', price: 0 },
    { id: 'headband', name: 'Повязка', price: 100 },
    { id: 'glasses', name: 'Очки', price: 200 },
    { id: 'wristband', name: 'Напульсник', price: 150 }
  ]
}

const canBuyItem = (itemId, price) => {
  return userStore.points >= price && !userStore.character.unlockedItems.includes(itemId)
}

const buyItem = async (itemId, price) => {
  if (canBuyItem(itemId, price)) {
    loading.value = true
    error.value = null

    try {
      // Отправка запроса на покупку предмета
      const response = await userApi.buyCharacterItem({ itemId, price })

      if (response.success) {
        // Обновление данных в хранилище
        userStore.buyCharacterItem(itemId, price)
      } else {
        error.value = response.message
      }
    } catch (err) {
      console.error('Ошибка при покупке предмета:', err)
      error.value = 'Не удалось купить предмет'
    } finally {
      loading.value = false
    }
  }
}

const equipItem = async (itemId, category) => {
  loading.value = true
  error.value = null

  try {
    // Отправка запроса на экипировку предмета
    const response = await userApi.equipCharacterItem({ itemId, category })

    if (response.success) {
      // Обновление данных в хранилище
      userStore.equipCharacterItem(itemId, category)
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при экипировке предмета:', err)
    error.value = 'Не удалось экипировать предмет'
  } finally {
    loading.value = false
  }
}

// Закрытие модального окна с сохранением изменений
const closeCustomization = async () => {
  await saveCharacterChanges()
  showCustomization.value = false
}

// Начать редактирование физических параметров
const startEditingStats = () => {
  newHeight.value = userStore.user?.info?.height || ''
  newWeight.value = userStore.user?.info?.weight || ''
  isEditingStats.value = true
}

// Сохранить новые физические параметры
const saveStats = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await userApi.updateUserInfo({
      height: newHeight.value ? Number(newHeight.value) : null,
      weight: newWeight.value ? Number(newWeight.value) : null
    })

    if (response.success) {
      userStore.setUserStats({
        height: newHeight.value ? Number(newHeight.value) : null,
        weight: newWeight.value ? Number(newWeight.value) : null
      })
      isEditingStats.value = false
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при обновлении физических параметров:', err)
    error.value = 'Не удалось обновить физические параметры'
  } finally {
    loading.value = false
  }
}

// Отменить редактирование физических параметров
const cancelEditingStats = () => {
  isEditingStats.value = false
}

onMounted(() => {
  fetchUserData()
})
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
      <div class="space-y-1">
        <!-- Режим просмотра имени -->
        <div v-if="!isEditingName" class="flex items-center gap-2">
          <h3 class="text-lg font-semibold dark:text-white">{{ userStore.user.name }}</h3>
          <button @click="startEditingName"
            class="text-gray-400 hover:text-violet-500 dark:text-gray-500 dark:hover:text-violet-400 transition-colors">
            <span class="i-mdi-pencil text-sm"></span>
          </button>
          <span v-if="loading" class="i-mdi-loading animate-spin text-xl text-violet-500"></span>
        </div>

        <div v-else class="flex items-center gap-2">

          <input v-model="newName" type="text"
            class="text-lg font-semibold px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-violet-500 dark:bg-gray-700 dark:text-white"
            @keyup.enter="saveNewName" @keyup.esc="cancelEditingName" ref="nameInput" v-focus>
          <div class="flex gap-1">
            <div v-if="loading" class="flex items-center justify-center">
              <span class="i-mdi-loading animate-spin text-xl text-violet-500"></span>
            </div>
            <div v-else>
              <button @click="saveNewName"
                class="text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300 transition-colors">
                <span class="i-mdi-check text-lg"></span>
              </button>
              <button @click="cancelEditingName"
                class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                <span class="i-mdi-close text-lg"></span>
              </button>
            </div>
          </div>
        </div>

        <!-- <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
          <span class="i-mdi-star text-yellow-500"></span>
          <span>{{ userStore.points }} очков</span>
        </div> -->
      </div>

      <!-- <BaseButton @click="showCustomization = true">
        Настроить
        <template #icon-right>
          <span class="i-mdi-pencil"></span>
        </template>
</BaseButton> -->
    </div>


    <div v-if="error" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-center">
      {{ error }}
      <BaseButton variant="secondary" size="sm" class="mt-2" @click="fetchUserData">
        Попробовать снова
      </BaseButton>
    </div>

    <div v-else>
      <div class="flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-xl p-8 mb-4">
        <div class="relative w-48 h-48">
          <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500">
            <span class="i-mdi-account text-8xl"></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Блок с физическими параметрами -->
    <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
      <div class="flex items-center justify-between mb-2">
        <h4 class="font-medium dark:text-white">Физические параметры</h4>
        <button v-if="!isEditingStats" @click="startEditingStats"
          class="text-gray-400 hover:text-violet-500 dark:text-gray-500 dark:hover:text-violet-400 transition-colors">
          <span class="i-mdi-pencil text-sm"></span>
        </button>
      </div>

      <!-- Режим просмотра параметров -->
      <div v-if="!isEditingStats" class="grid grid-cols-2 gap-4">
        <div class="flex flex-col">
          <span class="text-sm text-gray-500 dark:text-gray-400">Рост</span>
          <span class="font-medium dark:text-white">
            {{ userStore.user?.info?.height ? `${userStore.user.info.height} см` : 'Не указан' }}
          </span>
        </div>
        <div class="flex flex-col">
          <span class="text-sm text-gray-500 dark:text-gray-400">Вес</span>
          <span class="font-medium dark:text-white">
            {{ userStore.user?.info?.weight ? `${userStore.user.info.weight} кг` : 'Не указан' }}
          </span>
        </div>
      </div>

      <!-- Режим редактирования параметров -->
      <div v-else class="space-y-3">
        <div class="grid grid-cols-2 gap-4">
          <div class="flex flex-col">
            <label for="height" class="text-sm text-gray-500 dark:text-gray-400 mb-1">Рост (см)</label>
            <input id="height" v-model="newHeight" type="number" placeholder="Рост в см"
              class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-violet-500 dark:bg-gray-700 dark:text-white">
          </div>
          <div class="flex flex-col">
            <label for="weight" class="text-sm text-gray-500 dark:text-gray-400 mb-1">Вес (кг)</label>
            <input id="weight" v-model="newWeight" type="number" placeholder="Вес в кг"
              class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:border-violet-500 dark:bg-gray-700 dark:text-white">
          </div>
        </div>

        <div class="flex justify-end gap-2 mt-2">
          <BaseButton variant="secondary" size="sm" @click="cancelEditingStats">
            Отмена
          </BaseButton>
          <BaseButton variant="primary" size="sm" @click="saveStats">
            Сохранить
          </BaseButton>
        </div>
      </div>
    </div>
  </div>

  <div v-if="showCustomization" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold dark:text-white">Настройка персонажа</h3>
        <BaseButton variant="text" @click="closeCustomization">
          <span class="i-mdi-close text-xl"></span>
        </BaseButton>
      </div>

      <div v-if="loading" class="flex items-center justify-center py-8">
        <span class="i-mdi-loading animate-spin text-4xl text-violet-500"></span>
      </div>

      <div v-else-if="error"
        class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-center mb-4">
        {{ error }}
      </div>

      <div v-else class="space-y-6">
        <div>
          <h4 class="font-medium mb-3 dark:text-white">Прическа</h4>
          <div class="grid grid-cols-2 gap-2">
            <template v-for="item in characterParts.hair" :key="item.id">
              <BaseButton :variant="userStore.character.hair === item.id ? 'primary' : 'secondary'"
                :disabled="!userStore.character.unlockedItems.includes(item.id) && !canBuyItem(item.id, item.price)"
                @click="userStore.character.unlockedItems.includes(item.id)
                  ? equipItem(item.id, 'hair')
                  : buyItem(item.id, item.price)" class="justify-between">
                {{ item.name }}
                <span v-if="!userStore.character.unlockedItems.includes(item.id) && item.price > 0" class="text-sm">
                  {{ item.price }} ⭐
                </span>
              </BaseButton>
            </template>
          </div>
        </div>

        <div>
          <h4 class="font-medium mb-3 dark:text-white">Одежда</h4>
          <div class="grid grid-cols-2 gap-2">
            <template v-for="item in characterParts.clothes" :key="item.id">
              <BaseButton :variant="userStore.character.clothes === item.id ? 'primary' : 'secondary'"
                :disabled="!userStore.character.unlockedItems.includes(item.id) && !canBuyItem(item.id, item.price)"
                @click="userStore.character.unlockedItems.includes(item.id)
                  ? equipItem(item.id, 'clothes')
                  : buyItem(item.id, item.price)" class="justify-between">
                {{ item.name }}
                <span v-if="!userStore.character.unlockedItems.includes(item.id) && item.price > 0" class="text-sm">
                  {{ item.price }} ⭐
                </span>
              </BaseButton>
            </template>
          </div>
        </div>

        <div>
          <h4 class="font-medium mb-3 dark:text-white">Аксессуары</h4>
          <div class="grid grid-cols-2 gap-2">
            <template v-for="item in characterParts.accessories" :key="item.id">
              <BaseButton :variant="userStore.character.accessories === item.id ? 'primary' : 'secondary'"
                :disabled="!userStore.character.unlockedItems.includes(item.id) && !canBuyItem(item.id, item.price)"
                @click="userStore.character.unlockedItems.includes(item.id)
                  ? equipItem(item.id, 'accessories')
                  : buyItem(item.id, item.price)" class="justify-between">
                {{ item.name }}
                <span v-if="!userStore.character.unlockedItems.includes(item.id) && item.price > 0" class="text-sm">
                  {{ item.price }} ⭐
                </span>
              </BaseButton>
            </template>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <BaseButton @click="saveCharacterChanges" :disabled="loading">
          Сохранить изменения
          <template #icon-right>
            <span class="i-mdi-check"></span>
          </template>
        </BaseButton>
      </div>
    </div>
  </div>
</template>

<script>
// Директива для автофокуса на поле ввода
export default {
  directives: {
    focus: {
      mounted(el) {
        el.focus()
      }
    }
  }
}
</script>