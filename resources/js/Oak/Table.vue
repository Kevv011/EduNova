<script setup>
import { ref, computed } from 'vue';
import TableHeader from '@/Oak/TableHeader.vue';
import TableColumn from '@/Oak/TableColumn.vue';
import TableRow from '@/Oak/TableRow.vue';
import EmptyData from '@/Oak/EmptyData.vue';
import Pagination from '@/Oak/Pagination.vue';
const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
  headers: {
    type: Array,
    required: true,
  },
  showSelect: {
    type: Boolean,
    default: false,
  },
  idTable: {
    type: [String, Number],
    default: 'table-1',
  },
  sorter: {
    type: String,
    required: false,
  },
});

const emits = defineEmits('sort');
const people = [
  {
    name: 'Lindsay Walton',
    title: 'Front-end Developer',
    department: 'Optimization',
    email: 'lindsay.walton@example.com',
    role: 'Member',
    image:
      'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
  },
  {
    name: 'Courtney Henry',
    title: 'Front-end Developer',
    department: 'Optimization',
    email: 'lindsay.walton@example.com',
    role: 'Member',
    image:
      'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
  },
];
const selectedPeople = ref([]);
const indeterminate = computed(
  () =>
    selectedPeople.value.length > 0 &&
    selectedPeople.value.length < people.length,
);
</script>
<template>
  <div class="flow-root mt-8" v-if="items.data && items.data.length">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <div
          class="relative overflow-hidden shadow-sm ring-1 ring-gray-300 ring-opacity-5 sm:rounded-lg"
        >
          <div
            v-if="selectedPeople.length > 0"
            class="absolute top-0 flex items-center h-12 space-x-3 bg-white left-14 dark:bg-transparent sm:left-12"
          >
            <button
              type="button"
              class="inline-flex items-center px-2 py-1 text-sm font-semibold text-gray-900 bg-white rounded shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
            >
              Bulk edit
            </button>
            <button
              type="button"
              class="inline-flex items-center px-2 py-1 text-sm font-semibold text-gray-900 bg-white rounded shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white"
            >
              Delete all
            </button>
          </div>
          <table class="min-w-full divide-y divide-gray-300 table-fixed">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="relative px-7 sm:w-12 sm:px-6"
                  v-if="showSelect"
                >
                  <input
                    type="checkbox"
                    class="absolute w-4 h-4 -mt-2 text-indigo-600 border-gray-300 rounded left-4 top-1/2 focus:ring-indigo-600"
                    :checked="
                      indeterminate || selectedPeople.length === people.length
                    "
                    :indeterminate="indeterminate"
                    @change="
                      selectedPeople = $event.target.checked
                        ? people.map((p) => p.email)
                        : []
                    "
                  />
                </th>
                <template
                  v-for="(header, he) in headers"
                  :key="`header-${idTable}-${he}`"
                >
                  <slot :name="`header-${header.key}`" :header="header">
                    <TableHeader
                      :class="[
                        header.bold ? 'font-bold' : 'font-semibold',
                        {
                          'pr-3 pl-0': he === 0 && showSelect,
                          'px-3': !showSelect,
                        },
                      ]"
                      :header="header"
                      :sorter="sorter"
                      :is-first="he === 0"
                      :is-last="items.data.length - 1 === he"
                      @change-order="$emit('sort', $event)"
                    >
                      {{ header.title }}
                    </TableHeader>
                  </slot>
                </template>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <template
                v-for="(item, it) in items.data"
                :key="`row-${idTable}-${it}`"
              >
                <TableRow>
                  <TableColumn
                    v-for="(column, col) in headers"
                    :key="`col-${idTable}-${col}`"
                    :column="column"
                    :is-first="col === 0"
                    :is-last="items.data.length - 1 === col"
                  >
                    <template v-if="column.format">
                      <slot
                        :name="[column.key]"
                        :item="item"
                        :value="
                          item[column.key]
                            ? column.format(item[column.key])
                            : '----'
                        "
                        :index="it"
                        ><p :class="column.truncate ? 'w-64 truncate' : ''">
                          {{
                            item[column.key]
                              ? column.format(item[column.key])
                              : '----'
                          }}
                        </p></slot
                      >
                    </template>
                    <template v-else>
                      <slot
                        :name="[column.key]"
                        :item="item"
                        :value="item[column.key] ? item[column.key] : '----'"
                        :index="it"
                        ><p :class="column.truncate ? 'w-64 truncate' : ''">
                          {{
                            item[column.key] ||
                            typeof item[column.key] === 'number'
                              ? item[column.key]
                              : '----'
                          }}
                        </p></slot
                      >
                    </template>


                  </TableColumn>
                </TableRow>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="mt-10">
      <Pagination
        v-if="items.links"
        :links="items.links"
        :meta="items.meta"
        :id-pagination="idTable"
      />
    </div>
  </div>
  <EmptyData
    v-else
    title="No se encontró ningún resultado"
    description="Intenta cambiando los filtros seleccionados."
  />
</template>
