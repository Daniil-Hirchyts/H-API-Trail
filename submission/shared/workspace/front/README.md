# H-API Trail web client

Vue 3 and TypeScript interface for running and cycling events. The client included event browsing and details,
registration and login, participation lists, profile editing and event creation. It used Vue Router,
Tailwind CSS and Radix/shadcn-vue components.

## Development

From `submission/shared/workspace/front/`, with Node.js and pnpm 9.15 installed:

```bash
pnpm install --frozen-lockfile
pnpm dev
```

Set `VITE_API` to the event API base URL, including its `/api` prefix, and `VITE_ANNUAIRE` to the directory's
JSON API base URL. The API client sends credentials with requests and attempts token refresh after a 401 response.

```bash
pnpm build
pnpm preview
```

`build` runs the TypeScript checker and generates the static site in `dist/`. See the
[project documentation](../../../README.md) for the backend and directory components.
