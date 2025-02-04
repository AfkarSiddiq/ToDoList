<template>
    <div class="flex flex-col items-center justify-center">
        <!-- Judul -->
        <div class="mt-6 p-2">
            <div class="text-2xl font-bold">Todo-List</div>
        </div>

        <div class="ms-2 w-96 p-2">
            <!-- Tombol Tambah Item -->
            <div class="flex justify-end items-end mt-6 pe-4">
                <div class="pe-2">Tambah Item</div>
                <button @click="showModal = true">
                    <div
                        class="h-7 w-7 bg-green-400 rounded-full flex items-center justify-center text-white text-2xl font-bold cursor-pointer"
                    >
                        +
                    </div>
                </button>
            </div>

            <!-- To-Do List -->
            <div v-if="todoItems.length > 0">
                <label
                    v-for="(item, index) in todoItems"
                    :key="item.id"
                    class="bg-gray-100 m-2 p-2 rounded-md flex items-center justify-between"
                >
                    <div class="text-2xl font-bold">
                        {{ index + 1 }}. {{ item.title }}
                    </div>
                    <!-- Checkbox Bulat -->
                    <div class="flex items-center">
                        <div>
                            <input
                                type="checkbox"
                                v-model="item.completed"
                                @change="toggleComplete(item)"
                                class="hidden peer my-2 cursor-pointer"
                            />
                            <div
                                class="mx-2 w-6 h-6 border-2 border-blue-500 rounded-full flex items-center justify-center peer-checked:bg-blue-500"
                            >
                                <div
                                    class="w-3 h-3 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition"
                                ></div>
                            </div>
                        </div>
                        <div>
                            <button
                                @click="deleteItem(item)"
                                class="bg-red-500 text-white p-2 rounded-md"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Done Item -->
            <div class="m-2 flex justify-between items-center">
                <button
                @click="showDone = !showDone"
                class="">
                    {{ showDone ? 'Hide done items ' : 'Show done items' }}
                </button>
            </div>
            <div v-if="showDone && doneItems.length > 0" class="m-2 row">
                <div>
                    <label
                        v-for="(item, index) in doneItems"
                        :key="item.id"
                        class="bg-gray-100 m-2 p-2 rounded-md flex items-center justify-between cursor-pointer"
                    >
                        <div class="text-2xl font-bold text-gray-500">
                            {{ index + 1 }}. {{ item.title }}
                        </div>
                        <!-- Checkbox Bulat -->
                        <div class="flex items-center">
                            <div>
                                <input
                                    type="checkbox"
                                    v-model="item.completed"
                                    @change="toggleComplete(item)"
                                    class="hidden peer"
                                />
                                <div
                                    class="mx-2 w-6 h-6 border-2 border-blue-500 rounded-full flex items-center justify-center peer-checked:bg-blue-500"
                                >
                                    <div
                                        class="w-3 h-3 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition"
                                    ></div>
                                </div>
                            </div>
                            <div>
                                <button
                                    @click="deleteItem(item)"
                                    class="bg-red-500 text-white p-2 rounded-md mt-2"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- MODAL UNTUK MENAMBAH ITEM -->
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-80">
                <h2 class="text-xl font-bold mb-4">Tambah Todo</h2>
                <input
                    type="text"
                    v-model="newTodo"
                    class="w-full p-2 border border-gray-300 rounded-md"
                    placeholder="Masukkan judul todo..."
                />
                <div class="flex justify-end mt-4">
                    <button
                        @click="showModal = false"
                        class="px-4 py-2 bg-gray-300 rounded-md mr-2"
                    >
                        Batal
                    </button>
                    <button
                        @click="addItem"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            items: [], // Daftar todo
            showModal: false, // Status modal (true = tampil, false = sembunyi)
            newTodo: "", // Input untuk todo baru
            showDone: false, // Status done (true = tampil, false = sembunyi)
        };
    },
    computed: {
        //To-do data
        todoItems() {
            return this.items.filter((item) => !item.completed);
        },
        //Done data
        doneItems() {
            return this.items.filter((item) => item.completed);
        },
    },
    methods: {
        async fetchItems() {
            try {
                const response = await axios.get("/index");
                this.items = response.data.map((item) => ({
                    ...item,
                    completed: Boolean(item.completed), // Konversi ke boolean
                }));
            } catch (error) {
                console.error("Gagal mengambil data:", error);
            }
        },

        async addItem() {
            if (!this.newTodo.trim()) return; // Cegah input kosong

            try {
                const response = await axios.post("/store", {
                    title: this.newTodo,
                    completed: false,
                });

                this.items.push(response.data); // Tambahkan ke daftar
                this.newTodo = ""; // Kosongkan input
                this.showModal = false; // Tutup modal
            } catch (error) {
                console.error("Gagal menambah todo:", error);
            }
        },

        async toggleComplete(item) {
            try {
                await axios.put(`/edit/${item.id}`, {
                    completed: item.completed ? 1 : 0, // Update di database
                });
            } catch (error) {
                console.error("Gagal memperbarui status:", error);
            }
        },

        async deleteItem(item) {
            try {
                await axios.delete(`/delete/${item.id}`);
                this.items = this.items.filter((i) => i.id !== item.id);
            } catch (error) {
                console.error("Gagal menghapus todo:", error);
            }
        },

    },
    mounted() {
        this.fetchItems();
    },
};
</script>
