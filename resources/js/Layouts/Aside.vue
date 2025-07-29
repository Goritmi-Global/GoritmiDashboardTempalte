<template>
    <aside
        :class="[
            'transition-all duration-300',
            sidebarOpen ? 'w-72' : 'w-0 overflow-hidden',
        ]"
        class="bg-[#296FB6] text-white border-r border-gray-200 shadow-lg"
    >
        <!-- Navigation -->
        <nav class="py-6 px-4 space-y-2">
            <NavItem
                icon="home"
                text="Dashboard"
                href="/dashboard"
                :active="route().current('dashboard')"
            />

            <DisclosureItem
                icon="banknotes"
                text="Accounting"
                :open="accountingOpen"
                @toggle="accountingOpen = !accountingOpen"
                :active="isAccountingActive"
            >
                <NavItem
                    icon="arrows-right-left"
                    text="Transactions"
                    href="/accounting/transactions"
                    :active="route().current('accounting.transactions.index')"
                    small
                />
                <NavItem
                    icon="arrow-trending-down"
                    text="Expenses"
                    href="/accounting/expenses"
                    :active="route().current('accounting.expenses.index')"
                    small
                />
                <NavItem
                    icon="arrow-trending-up"
                    text="Incomes"
                    href="/accounting/incomes"
                    :active="route().current('accounting.incomes.index')"
                    small
                />
                <NavItem
                    icon="rectangle-stack"
                    text="Accounts"
                    href="/accounting/accounts"
                    :active="route().current('accounting.accounts.index')"
                    small
                />

                <NavItem
  icon="chart-bar"
  text="Reports"
  href="/accounting/reports"
  :active="route().current('accounting.reports.index')"
  small
/>



                <NavItem
                    icon="credit-card"
                    text="Expense Types"
                    href="/accounting/expense-types"
                    :active="route().current('accounting.expense-types.index')"
                    small
                />
                <NavItem
                    icon="wallet"
                    text="Income Types"
                    href="/accounting/income-types"
                    :active="route().current('accounting.income-types.index')"
                    small
                />

                <NavItem
                    icon="building-library"
                    text="Banks"
                    href="/accounting/banks"
                    :active="route().current('accounting.banks.index')"
                    small
                />
                <NavItem
                    icon="cash"
                    text="Cashbooks"
                    href="/accounting/cashbooks"
                    :active="route().current('accounting.cashbooks.index')"
                    small
                />
                <NavItem
                    icon="chart-bar"
                    text="Forecasting"
                    href="/accounting/forecasting"
                    :active="route().current('forecasting')"
                    small
                />
            </DisclosureItem>

            <NavItem
                icon="user-group"
                text="CRM"
                href="/crm"
                :active="route().current('crm')"
            />

            <!-- Grouped Section -->
            <div class="pt-4">
                <p
                    class="text-xs text-white/60 uppercase tracking-widest mb-1 px-2"
                >
                    Projects
                </p>
                <DisclosureItem
                    icon="folder"
                    text="Projects"
                    :open="projectsOpen"
                    @toggle="projectsOpen = !projectsOpen"
                    :active="isProjectActive"
                >
                    <NavItem
                        icon="plus"
                        text="New Projects"
                        href="/projects/new"
                        :active="route().current('projects.new')"
                        small
                    />
                    <NavItem
                        icon="briefcase"
                        text="Existing"
                        href="/projects/existing"
                        :active="route().current('projects.existing')"
                        small
                    />
                    <NavItem
                        icon="hand-thumb-up"
                        text="Dealing"
                        href="/projects/dealing"
                        :active="route().current('projects.dealing')"
                        small
                    />
                </DisclosureItem>
            </div>

            <!-- Users Section -->
            <div class="pt-4">
                <p
                    class="text-xs text-white/60 uppercase tracking-widest mb-1 px-2"
                >
                    Users
                </p>
                <DisclosureItem
                    icon="user-group"
                    text="Users"
                    :open="usersOpen"
                    @toggle="usersOpen = !usersOpen"
                    :active="isUserSubmenuActive"
                >
                    <NavItem
                        icon="document"
                        text="Manage Users"
                        href="/users"
                        :active="route().current('users.index')"
                        small
                    />
                    <NavItem
                        icon="document"
                        text="Roles & Permissions"
                        href="/roles-permissions"
                        :active="route().current('roles-permissions')"
                        small
                    />
                </DisclosureItem>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { computed, ref } from "vue";

import NavItem from "@/Components/NavItem.vue";
import DisclosureItem from "@/Components/DisclosureItem.vue";

const props = defineProps({
    sidebarOpen: Boolean,
});

const activeDropdown = ref(null);

const projectsOpen = ref(
    route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);

const usersOpen = ref(
    route().current("users.index") ||
        route().current("users") ||
        route().current("roles-permissions")
);

const isProjectActive = computed(
    () =>
        route().current("projects") ||
        route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);

const isUserSubmenuActive = computed(
    () =>
        route().current("users.index") ||
        route().current("users") ||
        route().current("roles-permissions")
);
const accountingOpen = ref(
    route().current("accounting.transactions.index") ||
        route().current("accounting.expense-types.index") ||
        route().current("accounting.income-types.index") ||
        route().current("accounting.expenses.index") ||
        route().current("accounting.incomes.index") ||
        route().current("accounting.accounts.index") ||
        route().current("accounting.banks.index") ||
        route().current("accounting.cashbooks.index") ||
        route().current("accounting.forecasting") ||
        route().current("accounting.reports.index")
        
);

const isAccountingActive = computed(() => accountingOpen.value);
</script>
