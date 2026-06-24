export function formatTaka(amount: number): string {
    return `৳ ${Number(amount).toLocaleString('en-BD')}`;
}
