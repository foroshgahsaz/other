#!/usr/bin/env python3
"""Extract publish-ready article body from blog markdown (strips YAML frontmatter)."""
from pathlib import Path

BLOG = Path(__file__).resolve().parent.parent / "blog"
OUT = Path(__file__).resolve().parent.parent / "blog" / "publish-ready"


def strip_frontmatter(text: str) -> str:
    if text.startswith("---"):
        end = text.find("---", 3)
        if end != -1:
            return text[end + 3 :].lstrip("\n")
    return text


def main() -> None:
    OUT.mkdir(parents=True, exist_ok=True)
    for path in sorted(BLOG.glob("*.md")):
        if path.parent.name == "publish-ready":
            continue
        body = strip_frontmatter(path.read_text(encoding="utf-8"))
        out_path = OUT / path.name
        out_path.write_text(body, encoding="utf-8")
        print(f"OK {path.name} -> publish-ready/{path.name}")


if __name__ == "__main__":
    main()
