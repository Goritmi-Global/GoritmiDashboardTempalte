<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { toast } from "vue3-toastify";

const props = defineProps({
    types: Array,
});

const showModal = ref(false);
const isEdit = ref(false);
const selectedType = ref(null);

const form = useForm({
    name: "",
});

const openCreateModal = () => {
    isEdit.value = false;
    form.name = "";
    selectedType.value = null;
    showModal.value = true;
};

const openEditModal = (type) => {
    isEdit.value = true;
    selectedType.value = type;
    form.name = type.name;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const submit = () => {
    const url = isEdit.value
        ? `/accounting/expense-types/${selectedType.value.id}`
        : "/accounting/expense-types";

    const method = isEdit.value ? form.put : form.post;

    method(url, {
        onSuccess: () => {
            toast.success(isEdit.value ? "Updated successfully" : "Created successfully");
            closeModal();
        },
        onError: () => {
            toast.error("Please fix the form errors.");
        },
    });
};

const destroy = (id) => {
    if (confirm("Are you sure you want to delete this record?")) {
        form.delete(`/accounting/expense-types/${id}`, {
            onSuccess: () => toast.success("Deleted successfully"),
            onError: () => toast.error("Failed to delete"),
        });
    }
};
</script>

<template>
    <AppLayout title="Expense Types">
        <Head title="Expense Types" />
        <div class="py-6 px-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Expense Types</h2>
                <button
                    @click="openCreateModal"
                    class="btn btn-primary rounded-pill shadow-sm"
                >
                    Add New
                </button>
            </div>

            <div class="table-responsive shadow-sm rounded-4 bg-white">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(type, index) in props.types" :key="type.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ type.name }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button
                                        class="btn btn-sm btn-outline-primary rounded-pill"
                                        @click="openEditModal(type)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                        @click="destroy(type.id)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.types.length">
                            <td colspan="3" class="text-center text-muted py-3">
                                No expense types found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <transition name="fade-modal">
            <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
                <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">
                        {{ isEdit ? "Edit Expense Type" : "Add Expense Type" }}
                    </h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium">Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control rounded-pill"
                                :class="{ 'is-invalid': form.errors.name }"
                            />
                            <div v-if="form.errors.name" class="text-danger text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="btn btn-outline-secondary rounded-pill"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="btn btn-primary rounded-pill"
                            >
                                {{ isEdit ? "Update" : "Create" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>

<style scoped>
.fade-modal-enter-active,
.fade-modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-modal-enter-from,
.fade-modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
.fade-modal-enter-to,
.fade-modal-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
