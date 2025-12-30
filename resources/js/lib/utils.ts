import type { Updater } from '@tanstack/vue-table';
import type { ClassValue } from 'clsx';
import { InertiaLinkProps } from '@inertiajs/vue3';

import type { Ref } from 'vue';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function valueUpdater<T extends Updater<any>>(updaterOrValue: T, ref: Ref) {
  ref.value = typeof updaterOrValue === 'function'
    ? updaterOrValue(ref.value)
    : updaterOrValue
}

interface Related {
  name: string
};

interface Row {
  getValue: (columnId: string) => Related
}

export const sortingFn = (rowA:Row, rowB:Row, columnId:string) => {
  const a:Related = rowA.getValue(columnId) || null;
  const b:Related = rowB.getValue(columnId) || null;

  const nameA = a?.name;
  const nameB = b?.name;

  if (nameA === undefined || nameA === null) return 1;
  if (nameB === undefined || nameB === null) return -1;

  return String(nameA).localeCompare(String(nameB), undefined, { numeric: true });
};