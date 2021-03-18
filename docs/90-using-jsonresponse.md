# Using JsonResponse

In this section, we refactor of existing code and use JsonResponse instead of an associative array

1. Add return type to interface class (`FinanceApiClientInterface`)
2. Modify typehints to inheritting classes (`YahooFinanceApiClient`, `FakeYahooFinanceApiClient`)
3. Modify returns to inheritting classes, returning `new JsonResponse(...)` rather than `returning [...]`
4. Modify usage of the responses (`$response['content']` into `$response->getContent()`)
5. Ensure that all tests still pass (run them between every step to figure out next)
