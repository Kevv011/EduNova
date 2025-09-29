import { reactive, watch } from "vue";
import { pickBy, throttle } from "lodash";
import { router } from "@inertiajs/vue3";

export function useFilters(values, url, options) {
    const params = reactive(values);

    watch(
        params,
        throttle(function () {
            const query = pickBy(params.filters);
            router.get(
                url,
                pickBy({
                    filter: query,
                    sort: params.sort,
                }),
                options
            );
        }, 150),
        { deep: true }
    );

    return params;
}
