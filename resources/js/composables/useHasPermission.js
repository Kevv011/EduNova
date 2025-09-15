import { usePage } from "@inertiajs/vue3";

const page = usePage();

export function useHasPermission(permission) {
    return page.props.permissions.includes(permission);
}
