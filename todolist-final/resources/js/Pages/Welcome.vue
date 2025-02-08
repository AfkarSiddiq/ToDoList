<template>
    <div :class="{ dark: isDarkMode }">
        <div
            class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white p-4"
        >
            <!-- Tombol Toggle Dark Mode -->
            <div class="flex justify-center items-center mb-4 text-bold text-2xl">
                To do List
            </div>
            <div class="flex justify-end">
                <button
                    @click="toggleDarkMode"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-black dark:text-white rounded-md"
                >
                    {{ isDarkMode ? "🌙 Dark Mode" : "☀️ Light Mode" }}
                </button>
            </div>

            <!-- Tombol Tambah Item -->
            <div v-if="!showModal" class="flex justify-end items-end mt-6 pe-4">
                <div class="pe-2">Tambah Item</div>
                <button @click="showModal = true" class="flex item-center">
                    <div
                        class="h-7 w-7 bg-green-400 rounded-full flex items-center justify-center text-white text-2xl font-bold cursor-pointer"
                    >
                        +
                    </div>
                </button>
            </div>

            <!-- Todo List -->
            <div class="mm-2 w-full p-2">
                <div v-if="todoItems.length > 0">
                    <div
                        v-for="(item, index) in todoItems"
                        :key="item.id"
                        class="bg-gray-300 shadow-md dark:bg-gray-800 text-gray-900 dark:text-white m-2 p-2 rounded-md flex items-center justify-between"
                    >
                        <div class="text-2xl font-bold">
                            {{ index + 1 }}. {{ item.title }}
                        </div>

                        <!-- Checkbox & Dropdown -->
                        <div class="flex items-center">
                            <Checkbox
                                v-model:checked="item.completed"
                                @change="toggleComplete(item)"
                                class="w-5 h-5 border-2 border-blue-500 rounded-md transition-transform duration-200 ease-in-out hover:scale-110 checked:bg-blue-500 checked:border-blue-500 cursor-pointer"
                            />
                            <Dropdown>
                                <template #trigger>
                                    <div
                                        class="h-7 w-7 bg-blue-400 dark:bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold cursor-pointer"
                                    >
                                        >
                                    </div>
                                </template>
                                <template #content>
                                    <div
                                        v-if="!modalEdit"
                                        class="py-1 bg-white dark:bg-gray-700"
                                    >
                                        <button
                                            @click="openEditModal(item)"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                                        >
                                            Edit
                                        </button>
                                    </div>
                                    <div class="py-1 bg-white dark:bg-gray-700">
                                        <button
                                            @click="deleteItem(item)"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>

                <!-- Done Item -->
                <div class="m-2 flex justify-between items-center">
                    <button @click="toggleShowDone" class="">
                        {{ showDone ? "Hide done items " : "Show done items" }}
                    </button>
                </div>
                <div v-if="showDone && doneItems.length > 0" class="m-2 row">
                    <div>
                        <div
                            v-for="(item, index) in doneItems"
                            :key="item.id"
                            class="bg-gray-300 shadow-md dark:bg-gray-700 m-2 p-2 rounded-md flex items-center justify-between"
                        >
                            <div class="text-2xl font-bold text-gray-500">
                                {{ index + 1 }}. {{ item.title }}
                            </div>
                            <!-- Checkbox Bulat -->
                            <div class="flex items-center">
                                <div>
                                    <Checkbox
                                        v-model:checked="item.completed"
                                        @change="toggleComplete(item)"
                                        class="w-5 h-5 border-2 border-blue-500 rounded-md transition-transform duration-200 ease-in-out hover:scale-110 checked:bg-blue-500 checked:border-blue-500 cursor-pointer"
                                    />
                                </div>
                                <div>
                                    <DangerButton
                                        @click="deleteItem(item)"
                                        class="bg-red-500 text-white p-2 rounded-md mt-2"
                                    >
                                        Hapus
                                    </DangerButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL UNTUK MENAMBAH ITEM -->
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white dark:bg-gray-600 dark:text-white p-6 rounded-lg shadow-lg w-80">
                <form @submit.prevent="addItem">
                    <h2 class="text-xl font-bold mb-4">Tambah Todo</h2>
                    <TextInput
                        type="text"
                        v-model="newTodo"
                        class="w-full p-2 border border-gray-300 rounded-md dark:bg-gray-400 dark:text-white"
                        placeholder="Masukkan judul todo..."
                        @keyup.enter="addItem"
                    />

                    <div class="flex justify-end mt-4">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 bg-gray-300 dark:text-black rounded-md mr-2"
                        >
                            Batal
                        </button>
                        <SubmitButton
                            type="submit"
                            class="px-4 py-2 bg-green-400 text-white rounded-md"
                        >
                            Tambah
                        </SubmitButton>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal untuk edit -->
        <div
            v-if="modalEdit"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg w-80">
                <form @submit.prevent="editItem">
                    <h2 class="text-xl font-bold mb-4 dark:text-white">Edit</h2>
                    <TextInput
                        type="text"
                        v-model="editTodo"
                        class="w-full p-2 border border-gray-300 dark:bg-gray-400 dark:text-white rounded-md"
                        placeholder="Masukkan judul todo..."
                    />

                    <div class="flex justify-end mt-4">
                        <button
                            type="button"
                            @click="modalEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded-md mr-2 dark:text-black"
                        >
                            Batal
                        </button>
                        <SubmitButton
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md"
                        >
                            Simpan
                        </SubmitButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import axios from "axios";

import { ref, computed, onMounted } from "vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";

interface Todo {
    id: number;
    title: string;
    completed: boolean;
}

// Reactive data
const items = ref<Todo[]>([]);
const showModal = ref(false);
const modalEdit = ref(false);
const newTodo = ref("");
const editTodo = ref("");
const showDone = ref<boolean>(
    JSON.parse(localStorage.getItem("showDone") || "false")
);
const isDarkMode = ref(localStorage.getItem("theme") === "dark");

// Computed properties
const todoItems = computed(() => items.value.filter((item) => !item.completed));
const doneItems = computed(() => items.value.filter((item) => item.completed));
const selectedTodo = ref<{ id: number; title: string } | null>(null);

// Methods
const fetchItems = async () => {
    try {
        const response = await axios.get("/index");
        items.value = response.data.map((item: any) => ({
            ...item,
            completed: Boolean(item.completed),
        }));
    } catch (error) {
        console.error("Gagal mengambil data:", error);
    }
};

const addItem = async () => {
    if (!newTodo.value.trim()) return;
    try {
        const response = await axios.post("/store", {
            title: newTodo.value,
            completed: false, // ✅ Ensure this is Boolean
        });

        // ✅ Ensure response has Boolean value
        const newTodoItem = {
            ...response.data,
            completed: !!response.data.completed,
        };

        items.value.push(newTodoItem);
        newTodo.value = "";
        showModal.value = false;
    } catch (error) {
        console.error("Gagal menambah todo:", error);
    }
};

const toggleComplete = async (item: { id: number; completed: boolean }) => {
    try {
        await axios.put(`/edit/${item.id}`, {
            completed: item.completed ? 1 : 0,
        });
    } catch (error) {
        console.error("Gagal memperbarui status:", error);
    }
};

const deleteItem = async (item: { id: number }) => {
    try {
        await axios.delete(`/delete/${item.id}`);
        items.value = items.value.filter((i) => i.id !== item.id);
    } catch (error) {
        console.error("Gagal menghapus todo:", error);
    }
};

const editItem = async () => {
    if (!selectedTodo.value) return; // Ensure an item is selected
    if (!editTodo.value.trim()) {
        console.error("Judul todo tidak boleh kosong!");
        return;
    }

    try {
        await axios.put(`/update/${selectedTodo.value.id}`, {
            title: editTodo.value,
        });

        // Update the local state
        items.value = items.value.map((i) =>
            i.id === selectedTodo.value?.id
                ? { ...i, title: editTodo.value }
                : i
        );

        modalEdit.value = false;
        selectedTodo.value = null; // Reset selected todo
    } catch (error) {
        console.error("Gagal mengedit todo:", error);
    }
};

const toggleShowDone = () => {
    showDone.value = !showDone.value;
    localStorage.setItem("showDone", showDone.value.toString());
};

const openEditModal = (item: { id: number; title: string }) => {
    selectedTodo.value = item;
    editTodo.value = item.title; // Set initial value
    modalEdit.value = true;
    console.log("selectedTodo", selectedTodo.value);
};

//darkMode
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark"); // Simpan pilihan ke localStorage
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
};
// Fetch items when component is mounted
onMounted(() => {
    fetchItems();
    onMounted(() => {
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }
    });
});
</script>
