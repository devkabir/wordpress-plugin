import {
  AdjustmentsVerticalIcon,
  ChatBubbleLeftEllipsisIcon,
  ClipboardDocumentListIcon,
} from "@heroicons/vue/24/outline";

let routes = [
  {
    path: "/",
    name: "Posts",
    component: () => import("@/pages/Posts.vue"),
    icon: ChatBubbleLeftEllipsisIcon,
  },
  {
    path: "/settings",
    name: "Settings",
    component: () => import("@/pages/Settings.vue"),
    icon: AdjustmentsVerticalIcon,
  },
  {
    path: "/logs",
    name: "Logs",
    component: () => import("@/pages/Logs.vue"),
    icon: ClipboardDocumentListIcon,
  },
];

export default routes;
