# FAQ

## Why not use Dependency Injection for this?
Good question. In fact, DI is a great technique and we'd recommend it every day.

It does, however solve an entirely _different_ problem.

With DI, you're still technically dealing with different classes in different
namespaces. While this might not necessarily matter, it might be a problem when
using typechecking. Using Mural, a `FooObject` is always an `instanceof` that
`FooObject`, even if it's really an alias for `BarObject`.

Also, using DI you'd have to know for sure your entire project (including any
packages with stuff you might want to override) uses the same DI framework. This
may or may not be the case; it's up to you to decide.

## Why not use [other solution]?
Be our guest - we found this to be the most elegant one :)

